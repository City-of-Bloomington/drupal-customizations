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
#$aliasStorage = \Drupal::service('path.alias_storage');

echo "\n";

define('UID', 1); // Drupal user to set as owner of the node

// Crosswalk for the department_ids
// $departments[$old_id => $new_id]
$departments = [
    17 => 26, // Animal Shelter
     1 => 16, // Clerk
     2 => 18, // CFRD
     3 => 19, // Controller
     4 => 16, // Council Office
    26 =>  3, // ESD
    13 => 21, // Fire
     6 => 22, // HAND
     7 =>  1, // ITS
     9 => 14, // OOTM
    10 =>  7, // Parks
    11 => 24, // Planning
    12 => 26, // Public Works
    20 => 26  // Sanitation
];

// Read the CSV line by line
$DATA = fopen('news.csv', 'r');
while (($row = fgets($DATA)) !== false) {
    list($date, $id, $dept, $title) = explode('<|>', trim($row));
    $created = strtotime($date);

    $type = 'news_release';
    #$title = 'Test';
    $content = file_get_contents("./news/$id.html");
    #$alias  = '/news'.date('/Y/m/d', $created);

    // Check if the alias already exists
    #if (!$aliasStorage->aliasExists($alias, $lang)) {
        // Create the new node
        $node = Node::create([
            'type'     => $type,
            'title'    => $title,
            'created'  => $created,
            'langcode' => $lang,
            'status'   => 1,
            'body'     => ['value' => $content, 'format' => 'basic_html'],
            'field_department' => [['target_id'=>$departments[$dept]]],
            #'path'     => ['alias' => $alias],
            'uid'      => UID
        ]);
        $node->save();
        echo "$date: $title\n";
    #}
}
