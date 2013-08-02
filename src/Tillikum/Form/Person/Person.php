<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Form\Person;

use Doctrine\ORM\EntityManager;
use Tillikum\ORM\EntityManagerAwareInterface;

class Person extends \Tillikum_Form implements EntityManagerAwareInterface
{
    protected $boundDisplayName;
    protected $em;

    public $person;

    public function bind($person)
    {
        $this->person = $person;
        $this->boundDisplayName = $person->display_name;

        if ($person->id) {
            $this->id->setValue($person->id);
        }

        $this->given_name->setValue($person->given_name);
        $this->middle_name->setValue($person->middle_name);
        $this->family_name->setValue($person->family_name);
        $this->display_name->setValue($person->display_name);
        $this->gender->setValue($person->gender);
        $this->tags->setValue(
            $person->tags->map(
                function ($tag) {
                    return $tag->id;
                }
            )
                ->toArray()
        );
        $this->note->setValue($person->note);

        return $this;
    }

    public function bindValues()
    {
        if (!$this->person) {
            return;
        }

        if ($this->id->getValue()) {
            $this->person->id = $this->id->getValue();
        }

        $this->person->given_name = $this->given_name->getValue();
        $this->person->middle_name = $this->middle_name->getValue();
        $this->person->family_name = $this->family_name->getValue();

        $newDisplayName = $this->display_name->getValue();

        if ($newDisplayName === $this->boundDisplayName) {
            $this->person->display_name = null;
        } else {
            $this->person->display_name = $newDisplayName;
        }

        $this->person->gender = $this->gender->getValue();
        if (count($this->tags->getValue()) > 0) {
            $this->person->tags = $this->em->getRepository('Tillikum\Entity\Person\Tag')
            ->findById($this->tags->getValue());
        } else {
            $this->person->tags->clear();
        }
        $this->person->note = $this->note->getValue();

        return $this;
    }

    public function init()
    {
        parent::init();

        $id = new \Zend_Form_Element_Hidden(
            'id',
            array(
                'decorators' => array(
                    'ViewHelper'
                )
            )
        );

        $givenName = new \Zend_Form_Element_Text(
            'given_name',
            array(
                'filters' => array(
                    'StringTrim'
                ),
                'label' => 'Given name'
            )
        );

        $middleName = new \Zend_Form_Element_Text(
            'middle_name',
            array(
                'filters' => array(
                    'StringTrim'
                ),
                'label' => 'Middle name'
            )
        );

        $familyName = new \Zend_Form_Element_Text(
            'family_name',
            array(
                'filters' => array(
                    'StringTrim'
                ),
                'label' => 'Family name'
            )
        );

        $displayName = new \Zend_Form_Element_Text(
            'display_name',
            array(
                'description' => 'This should be used if the person’s name'
                               . ' should not be formatted'
                               . ' “family, given middle”.',
                'filters' => array(
                    'StringTrim'
                ),
                'label' => 'Display name'
            )
        );

        $gender = new \Zend_Form_Element_Text(
            'gender',
            array(
                'filters' => array(
                    'StringTrim'
                ),
                'label' => 'Gender'
            )
        );

        $tags = new \Zend_Form_Element_Multiselect(
            'tags',
            array(
                'label' => 'Tags',
                'multiOptions' => array(),
            )
        );

        $note = new \Zend_Form_Element_Textarea(
            'note',
            array(
                'attribs' => array(
                    'class' => 'short',
                ),
                'filters' => array(
                    'StringTrim'
                ),
                'label' => 'Notes'
            )
        );

        $this->addElements(
            array(
                $id,
                $givenName,
                $middleName,
                $familyName,
                $displayName,
                $gender,
                $tags,
                $note,
                $this->createSubmitElement(array('label' => 'Save')),
            )
        );
    }

    public function setEntityManager(EntityManager $em)
    {
        $this->em = $em;

        $tagResult = $this->em
            ->createQuery(
                "
                SELECT t.id, t.name FROM Tillikum\Entity\Person\Tag t
                WHERE t.is_active = true
                "
            )
            ->getResult();

        $tagMultiOptions = array();
        foreach ($tagResult as $row) {
            $tagMultiOptions[$row['id']] = $row['name'];
        }

        $this->getElement('tags')
            ->setMultiOptions($tagMultiOptions)
            ->setAttrib('size', min(15, count($tagMultiOptions)));

        return $this;
    }
}
