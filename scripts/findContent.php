<?php
/**
 * Raw database queries to find content on the site
 *
 * This script does not need drush and can be run using php directly:
 * php findContent.php
 *
 * @copyright 2023-2025 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once '../web/autoload.php';
$request    = Request::createFromGlobals();
$kernel     = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$db         = \Drupal::database();

$pattern = 'href="(https:\/\/(?!bloomington)[^"]+)"';
$csv     = fopen('./results.csv', 'w');
fwrite($csv, "type,url,title,match\n");

$tables = [
    'node__body'                 => 'body_value',
    'node__field_aside'          => 'field_aside_value',
    'node__field_details'        => 'field_details_value',
    'node__field_related_links'  => 'field_related_links_uri',
    'node__field_call_to_action' => 'field_call_to_action_uri',
    'paragraph__field_info_link' => 'field_info_link_uri'
];

foreach ($tables as $table=>$column) {
    $sql    = "select b.entity_id,
                      a.alias,
                      d.type,
                      d.title,
                      b.$column
               from $table b
               join node_field_data d on d.nid=b.entity_id
               left join path_alias a on a.`path`=concat('/node/', b.entity_id)
               where regexp_like(b.$column, ?)";
    $query  = $db->query($sql, [$pattern]);
    $result = $query->fetchAll();
    foreach ($result as $row) {
        echo "https://bloomington.in.gov{$row->alias} {$row->title}}\n";
        $matches = [];
        preg_match_all("/$pattern/", $row->$column, $matches);
        foreach ($matches[1] as $match) {
            fputcsv($csv, [$row->type, "https://bloomington.in.gov{$row->alias}", $row->title, $match]);
        }
    }
}
