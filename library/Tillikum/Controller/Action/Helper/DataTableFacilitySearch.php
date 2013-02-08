<?php
/**
 * The Tillikum Project (http://tillikum.org/)
 *
 * @link       http://tillikum.org/websvn/
 * @copyright  Copyright 2009-2012 Oregon State University (http://oregonstate.edu/)
 * @license    http://www.gnu.org/licenses/gpl-2.0-standalone.html GPLv2
 */

namespace Tillikum\Controller\Action\Helper;

use DateTime;
use Zend_Controller_Action_Helper_Abstract as AbstractHelper;

class DataTableFacilitySearch extends AbstractHelper
{
    public function dataTableFacilitySearch(array $data)
    {
        $ac = $this->_actionController;

        $actions = array(
            'view' => $ac->getAcl()->isAllowed('_user', 'facility', 'read')
        );

        $rows = array();
        foreach ($data as $datum) {
            $config = $datum[0];

            $row = array(
                'actions' => $actions,
                'availableSpace' => $datum['availableSpace'],
                'capacity' => $config->capacity,
                'end' => $config->end ?: new DateTime('2099-01-01'),
                'facility_group' => $config->facility->facility_group->getConfigOnDate($config->start)->name,
                'facility' => $config->name,
                'gender' => $config->gender,
                'id' => $config->id,
                'start' => $config->start ?: new DateTime('1900-01-01'),
                'suite' => $config->suite,
                'note' => $config->note,
                'tags' => $config->tags,
                'view_uri' => $ac->getHelper('Url')->direct(
                    'view',
                    'facility',
                    'facility',
                    array(
                        'id' => $config->facility->id,
                    )
                ),
            );

            $rows[] = $row;
        }

        return $rows;
    }

    public function direct(array $data)
    {
        return $this->dataTableFacilitySearch($data);
    }
}
