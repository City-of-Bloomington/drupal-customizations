<?php
/**
 * @copyright 2017-2024 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Drupal\archive\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;

class ArchiveController extends ControllerBase
{
    /**
     * @param  string $type
     * @param  int    $year
     * @param  int    $month
     * @param  int    $day
     * @return array
     */
    public function archive($type, $year, $month, $day)
    {
        $year  = (int)$year;
        $month = (int)$month;
        $day   = (int)$day;

        if (!$year) { $year = (int)date('Y'); }

        if (!$month) {
            $date = "$year-01-01";
            $period = new \DateInterval('P1Y1D');
        }
        else {
            if (!$day) {
                $date   = "$year-$month-01";
                $period = new \DateInterval('P1M1D');
            }
            else {
                $date   = "$year-$month-$day";
                $period = new \DateInterval('P1D');
            }
        }
        $start = new \DateTime($date);
        $end   = new \DateTime($date);
        $end->add($period);

        $manager = \Drupal::entityTypeManager();
        $query   = \Drupal::entityQuery('node')
                 ->accessCheck(true)
                 ->condition('type',    $type)
                 ->condition('created', $start->format('U'), '>=')
                 ->condition('created', $end  ->format('U'), '<')
                 ->condition('status',  1)
                 ->sort('created', 'desc');

        $form_state = new FormState();
        $form_state->setValue('type',  $type);
        $form_state->setValue('year',  $year);
        $form_state->setValue('month', $month);
        $form = \Drupal::formBuilder()->buildForm('Drupal\archive\Form\DateForm', $form_state);

        return [
            '#theme'   => 'archive_results',
            '#year'    => $year,
            '#month'   => $month,
            '#start'   => $start,
            '#form'    => $form,
            '#type'    => $type,
            '#results' => $manager->getViewBuilder('node')->viewMultiple(
                              $manager->getStorage('node')->loadMultiple($query->execute()),
                              'teaser'
                          )
        ];
    }
}
