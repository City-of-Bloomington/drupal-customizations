<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\archive\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class DateForm extends FormBase
{
    public function getFormId() { return 'archive_dateform'; }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['#action'] = Url::fromRoute('archive.dateform')->toString();

        $form['type'] = [
            '#type' => 'hidden',
            '#default_value' => $form_state->getValue('type')
        ];

        $form['year'] = [
            '#title'         => 'Year',
            '#type'          => 'number',
            '#size'          => 4,
            '#step'          => 1,
            '#default_value' => $form_state->getValue('year'),
        ];

        $options = [];
        for ($i=1; $i<=12; $i++) { $options[$i] = date('F', mktime(0, 0, 0, $i, 10)); }
        $form['month'] = [
            '#title'         => 'Month',
            '#type'          => 'select',
            '#options'       => $options,
            '#default_value' => $form_state->getValue('month'),
            '#empty_option'  => 'All',
            '#empty_value'   => 0
        ];

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = [
            '#type'        => 'submit',
            '#value'       => 'Search',
            '#button_type' => 'primary'
        ];
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        $type = $form_state->getValue('type');
        if (!$type) {
            $form_state->setErrorByName('type', 'Missing Type');
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $type = $form_state->getValue('type');

        $year = (int)$form_state->getValue('year');
        if (!$year) { $year = (int)date('Y'); }
        $params = ['year'=>$year];

        $month = (int)$form_state->getValue('month');
        if ($month) { $params['month'] = $month; }

        $form_state->setRedirect("archive.$type", $params);
        return;
    }
}
