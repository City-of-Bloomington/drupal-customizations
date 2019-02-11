<?php
/**
 * @copyright 2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
use Drupal\Core\DrupalKernel;
use Drupal\node\Entity\Node;
use Drupal\Core\Path\AliasStorage;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once 'autoload.php';

$kernel   = new DrupalKernel('prod', $autoloader);
$request  = Request::createFromGlobals();
$response = $kernel->handle($request);

$storage  = Drupal::entityTypeManager()->getStorage('file');
$fids     = [];
$files    = $storage->loadMultiple($fids);
foreach ($files as $file) {
    $file->delete();
}
