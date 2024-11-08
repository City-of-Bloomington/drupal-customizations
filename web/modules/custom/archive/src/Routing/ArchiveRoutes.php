<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\archive\Routing;

use Symfony\Component\Routing\Route;

class ArchiveRoutes
{
    public function routes()
    {
        $routes  = [];
        $config  = \Drupal::config('archive.settings');
        $storage = \Drupal::entityTypeManager()->getStorage('node_type');

        foreach ($config->get('archive_types') as $t) {
            $type = $storage->load($t);

            $routes["archive.$t"] = new Route(
                "/$t/{year}/{month}/{day}",
                [
                    '_controller' => '\Drupal\archive\Controller\ArchiveController::archive',
                    '_title' => $type->get('name'),
                    'type'   => $t,
                    'year'   => 0,
                    'month'  => 0,
                    'day'    => 0
                ],
                [
                    '_permission' => 'access content',
                    'year'  => '\d+',
                    'month' => '\d+',
                    'day'   => '\d+'
                ]
            );
        }
        return $routes;
    }
}
