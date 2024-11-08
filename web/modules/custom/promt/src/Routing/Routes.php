<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt\Routing;

use Symfony\Component\Routing\Route;

class Routes
{
    public function routes()
    {
        $routes  = [];
        $config  = \Drupal::config('promt.settings');
        $base    = $config->get('promt_route');

        if (!$base) { $base = '/promt'; }

        $routes = [
            'promt.program'  => new Route(
                "$base/programs/{id}",
                [
                    '_controller' => '\Drupal\promt\Controller\PromtController::program',
                    '_title'      => 'Program'
                ],
                [ '_permission' => 'access content' ]
            ),
            'promt.programs' => new Route(
                "$base/programs",
                [
                    '_controller' => '\Drupal\promt\Controller\PromtController::programs',
                    '_title'      => 'Programs'
                ],
                [ '_permission' => 'access content' ]
            )
        ];

        return $routes;
    }
}
