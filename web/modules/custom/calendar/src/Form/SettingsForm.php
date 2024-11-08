<?php
/**
 * @copyright 2017-2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0 GNU/GPL2, see LICENSE
 */
namespace Drupal\calendar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase
{
    public function getFormId()
    {
        return 'calendar_settings';
    }

    public function getEditableConfigNames()
    {
        return ['calendar.settings'];
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('calendar.settings');

        $form['google_user_email'] = [
            '#type'          => 'textfield',
            '#title'         => 'Google User Email',
            '#default_value' => $config->get('google_user_email')
        ];
        $form['calendar_breadcrumb'] = [
            '#type'          => 'textfield',
            '#title'         => 'Breadcrumbs',
            '#default_value' => $config->get('calendar_breadcrumb'),
            '#description'   => 'Enter node IDs, separated by commas, to be used as the base breadcrumb for calendar routes'
        ];

        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('calendar.settings')
             ->set('google_user_email',   $form_state->getValue('google_user_email'))
             ->set('calendar_breadcrumb', $form_state->getValue('calendar_breadcrumb'))
             ->save();

        parent::submitForm($form, $form_state);
    }
}
