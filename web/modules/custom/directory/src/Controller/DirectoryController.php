<?php
/**
 * @copyright 2017-2024 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Drupal\directory\Controller;

use Drupal\Core\Controller\ControllerBase;

class DirectoryController extends ControllerBase
{
    public function directory()
    {
        $query = \Drupal::entityQuery('node')
                 ->accessCheck(true)
                 ->condition('status', 1)
                 ->condition('type', 'department')
                 ->sort('title');
        $nids = $query->execute();
        $departments = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);

        return [
            '#theme'       => 'directory_directory',
            '#departments' => $departments
        ];
    }
}
