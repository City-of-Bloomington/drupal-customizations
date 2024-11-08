<?php
/**
 * @copyright 2017-2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
declare (strict_types=1);
namespace Drupal\onboard\Plugin\Block;

use Drupal\onboard\OnBoardService;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Cache\Cache;
use Drupal\node\Entity\Node;

/**
 * Displays reports for a committee from OnBoard.
 *
 * @Block(
 *   id = "onboard_reports_block",
 *   admin_label = "Committee Reports"
 * )
 */
class ReportsBlock extends BlockBase implements BlockPluginInterface
{
    public function getCacheContexts()
    {
        return Cache::mergeContexts(parent::getCacheContexts(), ['url.path']);
    }

    public function build()
    {
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node && $node instanceof Node) {
            $settings  = \Drupal::config('onboard.settings');
            $fieldname = $settings->get('onboard_committee_field');


            if ($node && $node->hasField($fieldname)) {
                $id    = $node->get($fieldname)->value;
                if ($id) {
                    $json = OnBoardService::reports($id);
                    if (count($json)) {
                        return [
                            '#theme'       => 'onboard_reports',
                            '#reports'     => $json,
                            '#nid'         => $node->id(),
                            '#onboard_url' => OnBoardService::getUrl(),
                            '#cache'       => [
                                'max-age'  => 3600
                            ]
                        ];
                    }
                }
            }
        }
    }
}
