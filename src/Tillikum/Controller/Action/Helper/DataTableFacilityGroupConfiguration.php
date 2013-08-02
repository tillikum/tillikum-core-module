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
use RuntimeException;
use Tillikum\Entity;
use Vo\DateRange;
use Zend_Controller_Action_Helper_Abstract as AbstractHelper;

class DataTableFacilityGroupConfiguration extends AbstractHelper
{
    public function dataTableFacilityGroupConfiguration($facilityGroup)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        if (count($facilityGroup->configs) === 0) {
            return array();
        }

        if ($facilityGroup instanceof Entity\FacilityGroup\Building\Building) {
            return $this->buildingConfiguration($facilityGroup);
        }

        throw new RuntimeException(sprintf(
            $ac->t->translate(
                'No datatable action controller found for facility group subclass %s'
            ),
            get_class($facilityGroup)
        ));
    }

    protected function buildingConfiguration($facilityGroup)
    {
        $ac = $this->_actionController;
        $view = $ac->view;

        $actions = array(
            'delete' => $ac->getAcl()->isAllowed('_user', 'facility', 'write'),
            'edit' => $ac->getAcl()->isAllowed('_user', 'facility', 'write')
        );

        $rows = array();
        foreach ($facilityGroup->configs as $config) {
            $dateRange = new DateRange($config->start, $config->end);

            $row = array(
                'actions' => $actions,
                'id' => $config->id,
                'is_current' => $dateRange->includes(new DateTime(date('Y-m-d'))),
                'name' => $config->name,
                'gender' => $config->gender,
                'start' => $config->start ?: new DateTime('1900-01-01'),
                'end' => $config->end ?: new DateTime('2099-01-01'),
                'delete_uri' => $view->url(array(
                    'module' => 'facility',
                    'controller' => 'facilitygroupconfig',
                    'action' => 'delete',
                    'id' => $config->id
                ), null, true),
                'edit_uri' => $view->url(array(
                    'module' => 'facility',
                    'controller' => 'facilitygroupconfig',
                    'action' => 'edit',
                    'id' => $config->id
                ), null, true)
            );

            $rows[] = $row;
        }

        return $rows;
    }

    public function direct($facilityGroup)
    {
        return $this->dataTableFacilityGroupConfiguration($facilityGroup);
    }
}
