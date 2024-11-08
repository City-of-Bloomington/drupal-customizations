<?php
/**
 * @copyright 2017-2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
namespace Drupal\onboard\Plugin\Block;

use Drupal\onboard\OnBoardService;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Cache\Cache;
use Drupal\node\Entity\Node;

/**
 * Displays members for a committee from OnBoard.
 *
 * @Block(
 *   id = "onboard_members_block",
 *   admin_label = "Committee Members"
 * )
 */
class MembersBlock extends BlockBase implements BlockPluginInterface
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


            if ($node->hasField( $fieldname)) {
                $id = $node->get($fieldname)->value;
                if ($id) {
                    $json = OnBoardService::committee_info($id);
                    if (!empty($json['seats'])) {
                        usort($json['seats'], function ($a, $b) {
                            $as = $a['vacant'] ? 'Vacant' : $a['currentMember']['lastname'];
                            $bs = $b['vacant'] ? 'Vacant' : $b['currentMember']['lastname'];
                            if     ($as == $bs) { return 0; }
                            return ($as <  $bs) ? -1 : 1;
                        });
                    }
                    return [
                        '#theme'       => 'onboard_members',
                        '#committee'   => $json,
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
