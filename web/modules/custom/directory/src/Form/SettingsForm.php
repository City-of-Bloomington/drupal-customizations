<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\directory\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'directory_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames()
    {
        return ['directory.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('directory.settings');

        $form['directory_url'] = [
            '#type'          => 'textfield',
            '#title'         => 'Directory Url',
            '#default_value' => $config->get('directory_url')
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('directory.settings')
             ->set('directory_url', $form_state->getValue('directory_url'))
             ->save();

        parent::submitForm($form, $form_state);
    }
}
