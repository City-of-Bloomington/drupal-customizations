<?php
/**
 * @copyright 2017-2023 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
namespace Drupal\onboard\Controller;

use Drupal\onboard\OnBoardService;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OnBoardController extends ControllerBase
{
    private $fieldname = '';

    public function getCommitteeIdField()
    {
        if (!$this->fieldname) {
            $config  = \Drupal::config('onboard.settings');
            $this->fieldname = $config->get('onboard_committee_field');
        }
        return $this->fieldname;
    }

    public function meetings($node, $year)
    {
        $field = $this->getCommitteeIdField();

        if ($node->hasField($field) && $node->$field->value) {
            $year = (int)$year;
            if (!$year) { $year = (int)date('Y');  }

            $committee_id = $node->$field->value;

            $meetings = OnBoardService::meetings($committee_id, $year);
            $years    = OnBoardService::meetingFile_years($committee_id);

            return [
                '#theme'    => 'onboard_meetingFiles',
                '#title'    => $year,
                '#meetings' => $meetings,
                '#year'     => $year,
                '#years'    => $years,
                '#node'     => $node
            ];
        }
        throw new NotFoundHttpException();
    }

    public function reports($node)
    {
        $field = $this->getCommitteeIdField();

        if ($node->hasField($field) && $node->$field->value) {
            $committee_id = $node->$field->value;

            $reports = OnBoardService::reports($committee_id);

            return [
                '#theme'   => 'onboard_reports',
                '#title'   => 'Reports',
                '#reports' => $reports,
                '#node'    => $node
            ];
        }
        throw new NotFoundHttpException();
    }

    public function legislationTypes($node)
    {
        $field = $this->getCommitteeIdField();
        if ($node->hasField($field) && $node->$field->value) {
            $committee_id = $node->$field->value;

            $types = [];
            foreach (OnBoardService::legislation_types() as $t) {
                if (!$t['subtype']) { $types[] = $t['name']; }
            }
            return [
                '#theme' => 'onboard_legislationTypes',
                '#types' => $types,
                '#node'  => $node
            ];
        }
        throw new NotFoundHttpException();
    }

    public function legislationList($node, $type, $year)
    {
        if (!OnBoardService::typeExists($type)) {
            throw new NotFoundHttpException();
        }

        $field  = $this->getCommitteeIdField();
        if ($node->hasField($field) && $node->$field->value) {
            $year = $year ? (int)$year : (int)date('Y');

            $committee_id = $node->$field->value;

            $years       = OnBoardService::legislation_years($committee_id, $type);
            $legislation = OnBoardService::legislation_list([
                'committee_id' => $committee_id,
                'type'         => $type,
                'year'         => $year
            ]);

            $maxItems    = 10;
            $half        = (int)$maxItems/2;
            $currentYear = (int)date('Y');

            $maxYear = ($year + $half < $currentYear) ? $year + $half : $currentYear;

            $options = [];
            $c = 0;
            foreach (array_keys($years) as $y) {
                if ($y <= $maxYear) {
                    if ($c >= $maxItems) { break; }

                    $options["$y"] = $y;
                    $c++;
                }
            }

            return [
                '#theme'       => 'onboard_legislationList',
                '#title'       => $year,
                '#legislation' => $legislation,
                '#type'        => $type,
                '#year'        => $year,
                '#years'       => $options,
                '#node'        => $node
            ];
        }
        throw new NotFoundHttpException();
    }

    public function legislationYears($node, $type)
    {
        if (!OnBoardService::typeExists($type)) {
            throw new NotFoundHttpException();
        }
        $field = $this->getCommitteeIdField();

        if ($node->hasField($field) && $node->$field->value) {
            $committee_id = $node->$field->value;
            $years        = OnBoardService::legislation_years($committee_id, $type);

            $decades = [];
            foreach ($years as $y=>$data) {
                $d = (floor($y / 10)) * 10;
                $decades[$d][$y] = $data;
            }

            return [
                '#theme'   => 'onboard_legislationYears',
                '#title'   => $type,
                '#decades' => $decades,
                '#node'    => $node,
                '#type'    => $type,
                '#route'   => 'onboard.legislationList.node-'.$node->get('nid')->value
            ];
        }
        throw new NotFoundHttpException();
    }

    public function legislationInfo($node, $type, $year, $number)
    {
        if (!OnBoardService::typeExists($type)) {
            throw new NotFoundHttpException();
        }
        $field = $this->getCommitteeIdField();

        if ($node->hasField($field) && $node->$field->value) {
            $committee_id = $node->$field->value;

            $list = OnBoardService::legislation_list([
                'committee_id' => $committee_id,
                'type'         => $type,
                'year'         => $year,
                'number'       => $number
            ]);
            if (count($list) == 1) {
                return [
                    '#theme'       => 'onboard_legislationInfo',
                    '#title'       => "$type $number",
                    '#legislation' => $list[0],
                    '#node'        => $node
                ];
            }
        }
        throw new NotFoundHttpException();
    }

    public function meetingYears($node)
    {
        $field = $this->getCommitteeIdField();

        if ($node->hasField($field) && $node->$field->value) {
            $committee_id = $node->$field->value;

            $tonight      = new \DateTime('midnight');
            $start        = new \DateTime('-90 days');
            $end          = new \DateTime('+90 days');
            $meetings     = OnBoardService::meetings($committee_id, null, $start, $end);
            $upcoming     = [];
            $past         = [];
            foreach ($meetings as $d => $day) {
                $date = new \DateTime($d);
                foreach ($day as $event_id => $meeting) {
                    if (!empty($meeting['files'])) {
                        if ($date < $tonight) { $past[$d] = $day; }
                        else          { $upcoming[$d] = $day; }
                    }
                }
            }

            $decades = [];
            $years   = OnBoardService::meetingFile_years($committee_id);
            foreach ($years as $y=>$data) {
                $d = (floor($y / 10)) * 10;
                $decades[$d][$y] = $data;
            }

            return [
                '#theme'    => 'onboard_meetingYears',
                '#decades'  => $decades,
                '#node'     => $node,
                '#upcoming' => $upcoming,
                '#past'     => array_reverse($past),
                '#route'    => 'onboard.meetings.node-'.$node->get('nid')->value
            ];
        }
        throw new NotFoundHttpException();
    }

    public function legislationView($node, $type, $number)
    {
        $field = $this->getCommitteeIdField();

        $fields = [
            'committee_id' => $node->$field->value,
            'type'         => $type,
            'number'       => $number
        ];
        $list = OnBoardService::legislation_find($fields);
        if (count($list) === 1) {
            return [
                '#theme'       => 'onboard_legislationInfo',
                '#legislation' => $list[0],
                '#node'        => $node

            ];
        }
        throw new NotFoundHttpException();
    }
}
