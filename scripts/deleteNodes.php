<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
use Drupal\Core\DrupalKernel;
use Drupal\node\Entity\Node;
use Drupal\Core\Path\AliasStorage;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once 'autoload.php';

$kernel   = new DrupalKernel('prod', $autoloader);
$request  = Request::createFromGlobals();
$response = $kernel->handle($request);

$nodes = Drupal::entityTypeManager()
       ->getStorage('node')
       ->loadByProperties(['type'=>'news_release']);

foreach ($nodes as $n) { $n->delete(); }
