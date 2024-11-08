<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
namespace Drupal\onboard\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase
{
    public function getFormId()              { return  'onboard_settings';  }
    public function getEditableConfigNames() { return ['onboard.settings']; }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('onboard.settings');

        $form['onboard_url'] = [
            '#type'          => 'textfield',
            '#title'         => 'OnBoard Url',
            '#default_value' => $config->get('onboard_url')
        ];

        $form['onboard_committee_field'] = [
            '#type'          => 'textfield',
            '#title'         => 'Committee ID Field',
            '#default_value' => $config->get('onboard_committee_field')
        ];

        $options = [];
        $checked = $config->get('onboard_types');
        $types   = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
        foreach ($types as $t) {
            $options[$t->get('type')] = $t->get('name');
        }

        $form['onboard_types'] = [
            '#type'          => 'checkboxes',
            '#title'         => 'Content Types',
            '#description'   => 'Select content types to generate meeting routes for.',
            '#options'       => $options,
            '#default_value' => $checked ? $checked : []
        ];
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $types = [];
        foreach ($form_state->getValue('onboard_types') as $type=>$checked) {
            if ($checked) { $types[] = $type; }
        }

        $this->config('onboard.settings')
             ->set('onboard_url',             $form_state->getValue('onboard_url'))
             ->set('onboard_committee_field', $form_state->getValue('onboard_committee_field'))
             ->set('onboard_types',           $types)
             ->save();

        parent::submitForm($form, $form_state);
    }
}
