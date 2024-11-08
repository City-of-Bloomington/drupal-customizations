<?php
/**
 * @copyright 2017-2024 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
declare (strict_types=1);
namespace Drupal\onboard\Routing;

use Drupal\onboard\OnBoardService;
use Symfony\Component\Routing\Route;

class Routes
{
    private static $legislative = [];

    public function routes()
    {
        $routes  = [];
        $config  = \Drupal::config('onboard.settings');
        $types   = $config->get('onboard_types');
        $field   = $config->get('onboard_committee_field');


        $committees = OnBoardService::committee_list();
        foreach ($committees as $c) {
            if ($c['legislative']) { self::$legislative[] = $c['id']; }
        }


        if ($types) {
            $aliasManager = \Drupal::service('path_alias.manager');
            $storage      = \Drupal::entityTypeManager()->getStorage('node');

            $nids = \Drupal::entityQuery('node')
                    ->accessCheck(true)
                    ->condition('status', 1)
                    ->condition('type', $types)
                    ->execute();
            $nodes = $storage->loadMultiple($nids);

            $legislationTypes = OnBoardService::legislation_types();

            foreach ($nodes as $node) {
                $nid          = $node->get('nid' )->value;
                $committee_id = $node->get($field)->value;

                $alias = $aliasManager->getAliasByPath("/node/$nid");
                $routes["onboard.meetingList.node-$nid"] = new Route(
                    "$alias/meetings/{year}",
                    [
                        '_controller' => '\Drupal\onboard\Controller\OnBoardController::meetings',
                        '_title'      => 'Meetings',
                        'node'        => $nid
                    ],
                    [
                        '_permission' => 'access content',
                        'year'        => '\d+'
                    ],
                    ['parameters' => ['node' => ['type'=>'entity:node']]]
                );
                $routes["onboard.meetingYears.node-$nid"] = new Route(
                    "$alias/meetings",
                    [
                        '_controller' => '\Drupal\onboard\Controller\OnBoardController::meetingYears',
                        '_title'      => 'Meeting Documents',
                        'node'        => $nid
                    ],
                    ['_permission' => 'access content' ],
                    ['parameters'  => ['node' => ['type'=>'entity:node']]]
                );
                $routes["onboard.reports.node-$nid"] = new Route(
                    "$alias/reports",
                    [
                        '_controller' => '\Drupal\onboard\Controller\OnBoardController::reports',
                        '_title'      => 'Reports',
                        'node'        => $nid
                    ],
                    ['_permission' => 'access content' ],
                    ['parameters'  => ['node' => ['type'=>'entity:node']]]
                );

                if (in_array((int)$committee_id, self::$legislative)) {
                    $routes["onboard.legislationTypes.node-$nid"] = new Route(
                        "$alias/legislation",
                        [
                            '_controller' => '\Drupal\onboard\Controller\OnBoardController::legislationTypes',
                            '_title'      => 'Legislation',
                            'node'        => $nid,
                        ],
                        ['_permission' => 'access content'],
                        ['parameters'  => ['node' => ['type'=>'entity:node']]]
                    );
                    $routes["onboard.legislationList.node-$nid"] = new Route(
                        "$alias/legislation/{type}/{year}",
                        [
                            '_controller' => '\Drupal\onboard\Controller\OnBoardController::legislationList',
                            '_title'      => 'Legislation',
                            'node'        => $nid,
                        ],
                        [
                            '_permission' => 'access content',
                            'year'        => '\d+'
                        ],
                        ['parameters' => ['node' => ['type'=>'entity:node']]]
                    );
                    $routes["onboard.legislationYears.node-$nid"] = new Route(
                        "$alias/legislation/{type}",
                        [
                            '_controller' => '\Drupal\onboard\Controller\OnBoardController::legislationYears',
                            '_title'      => 'Archive',
                            'node'        => $nid
                        ],
                        ['_permission' => 'access content'],
                        ['parameters'  => ['node' => ['type'=>'entity:node']]]
                    );
                    $routes["onboard.legislationInfo.node-$nid"] = new Route(
                        "$alias/legislation/{type}/{year}/{number}",
                        [
                            '_controller' => '\Drupal\onboard\Controller\OnBoardController::legislationInfo',
                            '_title'      => 'Archive',
                            'node'        => $nid
                        ],
                        ['_permission' => 'access content'],
                        ['parameters'  => ['node' => ['type'=>'entity:node']]]
                    );
                }
            }
        }
        return $routes;
    }
}
