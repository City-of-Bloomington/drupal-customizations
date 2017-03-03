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
//$response->send();
//$kernel->terminate($request, $response);

$lang         = \Drupal::languageManager()->getCurrentLanguage()->getId();
$aliasStorage = \Drupal::service('path.alias_storage');

echo "\n";

define('UID', 1); // Drupal user to set as owner of the node

// Read the CSV line by line
#$CSV = fopen('file.csv', 'r');
#while (($row = fgetcsv($CSV)) !== false) {
    $type = 'basic_page';
    $title = 'Test';
    $content = '<h2>Test</h2>';
    $alias  = '/test';

    // Check if the alias already exists
    if (!$aliasStorage->aliasExists($alias, $lang)) {
        // Create the new node
        $node = Node::create([
            'type'     => $type,
            'title'    => $title,
            'langcode' => $lang,
            'status'   => 1,
            'body'     => ['value' => $content, 'format' => 'basic_html'],
            'path'     => ['alias' => $alias],
            'uid'      => UID
        ]);
        $node->save();
        echo "Saved test\n";
    }
#}
