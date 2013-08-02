<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Job;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Tillikum\Entity;
use Tillikum\Job\AbstractJob;
use Zend\Di\Di;

class BillingEventProcessor extends AbstractJob
{
    public function __construct(EntityManager $em, Di $di)
    {
        $this->di = $di;
        $this->em = $em;
    }

    public function getDescription()
    {
        return 'Process billing events into billing entries according to the ' .
               'billing rules.';
    }

    public function getFormClass()
    {
        return 'Tillikum\Form\Job\BillingEventProcessor';
    }

    public function getName()
    {
        return 'Billing event processor';
    }

    public function run()
    {
        $job = $this->getJobEntity();
        $parameters = $this->getParameters();

        $csvResource = fopen('php://temp/maxmemory:' . 16 * 1024 * 1024, 'r+');

        fputcsv(
            $csvResource,
            array(
                'Event ID',
                'Invoicee ID',
                'Invoicee family name',
                'Invoicee given name',
                'Invoice description',
                'Entry currency',
                'Entry amount',
                'Entry code',
                'Entry description',
            )
        );

        $rawEvents = $this->em->createQuery(
            "
            SELECT e
            FROM Tillikum\Entity\Billing\Event\Event e
            WHERE e.is_processed = :isProcessed
            ORDER BY e.created_at
            "
        )
            ->setParameter('isProcessed', false)
            ->getResult();

        $entryCount = 0;
        $eventCount = 0;
        $invoiceCount = 0;
        $rawEventGroups = array();
        $deduplicatedEvents = array();
        foreach ($rawEvents as $event) {
            if ($event instanceof Entity\Billing\Event\FacilityBooking) {
                $groupHash = md5(
                    $event->person->id .
                    $event->rule->id .
                    $event->facility->id .
                    $event->start->format('Y-m-d') .
                    $event->end->format('Y-m-d')
                );

                $rawEventGroups[$groupHash][] = $event;
            } elseif ($event instanceof Entity\Billing\Event\MealplanBooking) {
                $groupHash = md5(
                    $event->person->id .
                    $event->rule->id .
                    $event->mealplan->id .
                    $event->start->format('Y-m-d') .
                    $event->end->format('Y-m-d')
                );

                $rawEventGroups[$groupHash][] = $event;
            } else {
                $deduplicatedEvents[] = $event;
            }

            if (!$job->is_dry_run) {
                $event->is_processed = true;
            }

            $eventCount += 1;
        }

        foreach ($this->deduplicateEvents($rawEventGroups) as $event) {
            $deduplicatedEvents[] = $event;
        }

        $invoices = array();
        foreach ($deduplicatedEvents as $event) {
            if ($event instanceof Entity\Billing\Event\AdHoc) {
                $processor = $this->di->get(
                    'Tillikum\Billing\Event\Processor\AdHocProcessor'
                );
            } elseif ($event instanceof Entity\Billing\Event\AbstractBooking) {
                $processor = $this->di->get(
                    'Tillikum\Billing\Event\Processor\BookingProcessor'
                );
            } else {
                $message = new Entity\Job\Message\Message();
                $message->job = $job;
                $message->level = LOG_WARNING;
                $message->message = "Unable to process event ID {$event->id}" .
                                    ' due to a lack of an appropriate billing' .
                                    ' processor. This is an error that a' .
                                    ' support staff member will need to' .
                                    ' address.';
                $this->em->persist($message);

                if (!$job->is_dry_run) {
                    $event->is_processed = false;
                }

                $eventCount -= 1;

                continue;
            }

            try {
                $entries = $processor->process($event);

                if (!$job->is_dry_run) {
                    foreach ($entries as $entry) {
                        $event->addEntry($entry);
                    }
                }
            } catch (\Exception $e) {
                $message = new Entity\Job\Message\Message();
                $message->job = $job;
                $message->level = LOG_WARNING;
                $message->message = "Unable to process event ID {$event->id}: " .
                                    $e->getMessage();
                $this->em->persist($message);

                if (!$job->is_dry_run) {
                    $event->is_processed = false;
                }

                $eventCount -= 1;

                continue;
            }

            foreach ($entries as $entry) {
                if (!$job->is_dry_run) {
                    $this->em->persist($entry);
                }

                $entryCount += 1;

                if (!isset($invoices[$event->person->id])) {
                    $invoices[$event->person->id] = new Entity\Billing\Invoice\Invoice();
                    $invoice = $invoices[$event->person->id];
                    $invoice->person = $event->person;
                    $invoice->description = $parameters['invoice_description'];
                    $invoice->created_by = $parameters['identity'];

                    if (!$job->is_dry_run) {
                        $this->em->persist($invoice);
                    }

                    $invoiceCount += 1;
                }

                $invoice = $invoices[$event->person->id];

                $entry->invoice = $invoice;
                $entry->created_by = $parameters['identity'];

                fputcsv(
                    $csvResource,
                    array(
                        $event->id,
                        $invoice->person->id,
                        $invoice->person->family_name,
                        $invoice->person->given_name,
                        $invoice->description,
                        $entry->currency,
                        $entry->amount,
                        $entry->code,
                        $entry->description,
                    )
                );
            }
        }

        rewind($csvResource);
        $csv = stream_get_contents($csvResource);
        fclose($csvResource);

        $attachment = new Entity\Job\Attachment\Attachment();
        $attachment->job = $job;
        $attachment->name = $parameters['invoice_description'] . '.csv';
        $attachment->media_type = 'text/csv';
        $attachment->attachment = $csv;
        $this->em->persist($attachment);

        $message = new Entity\Job\Message\Message();
        $message->job = $job;
        $message->level = LOG_INFO;
        $message->message = "Processed {$eventCount} events.";
        $this->em->persist($message);

        $message = new Entity\Job\Message\Message();
        $message->job = $job;
        $message->level = LOG_INFO;
        $message->message = "Generated {$invoiceCount} invoices.";
        $this->em->persist($message);

        $message = new Entity\Job\Message\Message();
        $message->job = $job;
        $message->level = LOG_INFO;
        $message->message = "Generated {$entryCount} entries.";
        $this->em->persist($message);

        $job->run_state = Entity\Job\Job::RUN_STATE_STOPPED;
        $job->job_state = Entity\Job\Job::JOB_STATE_SUCCESS;

        $this->em->flush();
    }

    protected function deduplicateEvents(array $rawEventGroups)
    {
        $deduplicatedEvents = array();
        foreach ($rawEventGroups as $groupHash => $group) {
            $eventsToSkip = array();
            for ($i = 0, $count = count($group); $i < $count; $i++) {
                $dupeFound = false;
                $outerEvent = $group[$i];
                if (in_array($i, $eventsToSkip)) {
                    continue;
                }

                for ($j = $i + 1, $count = count($group); $j < $count; $j++) {
                    $innerEvent = $group[$j];
                    if (in_array($j, $eventsToSkip)) {
                        continue;
                    }

                    if ($innerEvent->is_credit === !$outerEvent->is_credit) {
                        $dupeFound = true;
                        $eventsToSkip[] = $i;
                        $eventsToSkip[] = $j;
                        break;
                    }
                }

                if (!$dupeFound) {
                    $deduplicatedEvents[] = $outerEvent;
                }
            }
        }

        return $deduplicatedEvents;
    }
}
