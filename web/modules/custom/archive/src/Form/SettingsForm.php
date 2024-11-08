<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\archive\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase
{
    public function getFormId()              { return  'archive_settings';  }
    public function getEditableConfigNames() { return ['archive.settings']; }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config  = $this->config('archive.settings');

        $options = [];
        $checked = $config->get('archive_types');
        $types   = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
        foreach ($types as $t) {
            $options[$t->get('type')] = $t->get('name');
        }

        $form['archive_types'] = [
            '#type'          => 'checkboxes',
            '#title'         => 'Content Types',
            '#description'   => 'Select content types to active archive routes for.',
            '#options'       => $options,
            '#default_value' => $checked ? $checked : []
        ];

        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $types = [];
        foreach ($form_state->getValue('archive_types') as $type=>$checked) {
            if ($checked) { $types[] = $type; }
        }
        $this->config('archive.settings')
             ->set('archive_types', $types)
             ->save();
        parent::submitForm($form, $form_state);
    }
}
