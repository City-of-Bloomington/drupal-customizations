<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormState;
use Drupal\promt\PromtService;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PromtController extends ControllerBase
{
    public function programs()
    {
        $form_state = new FormState();
        $form_state->setAlwaysProcess(true);
        $form_state->setRebuild(true);

        if (!empty($_GET['category_id'])) { $form_state->set('category_id', (int)$_GET['category_id']); }
        if (!empty($_GET['location_id'])) { $form_state->set('location_id', (int)$_GET['location_id']); }
        if (!empty($_GET['ageGroup'   ])) { $form_state->set('ageGroup'   ,      $_GET['ageGroup'   ]); }

        $form = \Drupal::formBuilder()->buildForm('Drupal\promt\Form\ProgramSearchForm', $form_state);

        $programs = PromtService::programs($_GET);
        return [
            '#theme'   => 'promt_search',
            '#form'    => $form,
            '#results' => $programs
        ];
    }

    public function program($id)
    {
        $program = PromtService::program((int)$id);

        if (!$program) {
            throw new NotFoundHttpException();
        }

        return [
            '#theme'   => 'promt_program',
            '#program' => $program,
            '#title'   => $program['title']
        ];
    }
}
