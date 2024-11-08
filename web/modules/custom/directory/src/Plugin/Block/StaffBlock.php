<?php
/**
 * @copyright 2017-2021 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Drupal\directory\Plugin\Block;

use Drupal\directory\DirectoryService;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * @Block(
 *     id = "staff_block",
 *     admin_label = "Department Staff"
 * )
 */
class StaffBlock extends BlockBase implements BlockPluginInterface
{
    public function getCacheContexts()
    {
        return Cache::mergeContexts(parent::getCacheContexts(), ['url.path']);
    }

    public function build()
    {
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node && $node instanceof Node) {
            $config    = $this->getConfiguration();
            $fieldname = !empty($config['fieldname']) ? $config['fieldname'] : 'field_directory_dn';

            if ($node->hasField( $fieldname)) {
                $dn = $node->get($fieldname)->value;
                if ($dn) {
                    $json = DirectoryService::department_info($dn);
                    if (!empty($json['people'])) {
                        return [
                            '#theme'      => 'directory_staff',
                            '#department' => $json,
                            '#cache'       => [
                                'max-age'  => 3600
                            ]
                        ];
                    }
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function blockForm($form, FormStateInterface $form_state)
    {
        $form   = parent::blockForm($form, $form_state);
        $config = $this->getConfiguration();

        $form['staff_block_fieldname'] = [
            '#type' => 'textfield',
            '#title' => 'Fieldname',
            '#description' => 'Name of the node field that contains the Directory DN',
            '#default_value' => isset($config['fieldname']) ? $config['fieldname'] : ''
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function blockSubmit($form, FormStateInterface $form_state)
    {
        $this->configuration['fieldname'] = $form_state->getValue('staff_block_fieldname');
    }
}
