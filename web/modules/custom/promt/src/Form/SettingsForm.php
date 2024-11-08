<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class SettingsForm extends ConfigFormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'promt_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function getEditableConfigNames()
    {
        return ['promt.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $config = $this->config('promt.settings');

        $form['promt_url'] = [
            '#type'          => 'textfield',
            '#title'         => 'PROMT Url',
            '#default_value' => $config->get('promt_url')
        ];
        $form['promt_breadcrumb'] = [
            '#type'          => 'textfield',
            '#title'         => 'Breadcrumbs',
            '#default_value' => $config->get('promt_breadcrumb'),
            '#description'   => 'Enter node IDs, separated by commas, to be used as the base breadcrumb for Promt routes'
        ];
        $form['promt_route'] = [
            '#type'          => 'textfield',
            '#title'         => 'Base Route',
            '#default_value' => $config->get('promt_route'),
            '#description'   => "Specify an alias to use as the base for this module's routes"
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $this->config('promt.settings')
             ->set('promt_url',        $form_state->getValue('promt_url'       ))
             ->set('promt_breadcrumb', $form_state->getValue('promt_breadcrumb'))
             ->set('promt_route',      $form_state->getValue('promt_route'     ))
             ->save();

        parent::submitForm($form, $form_state);
    }
}
