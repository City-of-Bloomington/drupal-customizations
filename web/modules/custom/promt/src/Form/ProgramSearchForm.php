<?php
/**
 * @copyright 2017-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Drupal\promt\Form;

use Drupal\promt\PromtService;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class ProgramSearchForm extends FormBase
{
    public function getFormId() { return 'promt_programsearchform'; }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['#method'] = 'get';
        $form['#action'] = Url::fromRoute('promt.programs')->toString();

        $categories = PromtService::categories();
        $options    = [];
        foreach ($categories as $c) { $options[$c['id']] = $c['name']; }
        $form['category_id'] = [
            '#type'         => 'select',
            '#title'        => 'Category',
            '#options'      => $options,
            '#default_value' => $form_state->get('category_id'),
            '#empty_option' => '',
            '#empty_value'  => ''
        ];

        $locations = PromtService::locations();
        $options   = [];
        foreach ($locations as $l) { $options[$l['id']] = $l['name']; }
        $form['location_id'] = [
            '#type'    => 'select',
            '#title'   => 'Location',
            '#options' => $options,
            '#default_value' => $form_state->get('location_id'),
            '#empty_option' => '',
            '#empty_value'  => ''
        ];

        $ageGroups = PromtService::ageGroups();
        $options   = [];
        foreach ($ageGroups as $a) { $options[$a] = $a; }
        $form['ageGroup'] = [
            '#type'         => 'select',
            '#title'        => 'Age Group',
            '#options'      => $options,
            '#default_value' => $form_state->get('ageGroup'),
            '#empty_option' => '',
            '#empty_value'  => ''
        ];


        $form['actions'] = [
            '#type'  => 'actions',
            'submit' => [
                '#type'        => 'submit',
                '#value'       => 'Search',
                '#button_type' => 'primary'
            ]
        ];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $form_state->setRebuild();
    }
}
