<?php
/**
 * @copyright 2023 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once 'autoload.php';
$request    = Request::createFromGlobals();
$kernel     = DrupalKernel::createFromRequest($request, $autoloader, 'prod');
$kernel->boot();
$db         = \Drupal::database();

$pattern = '%bton%';

$tables = [
    'node__body'                 => 'body_value',
    'node__field_aside'          => 'field_aside_value',
    'node__field_details'        => 'field_details_value',
    'node__field_related_links'  => 'field_related_links_uri',
    'node__field_call_to_action' => 'field_call_to_action_uri',
    'paragraph__field_info_link' => 'field_info_link_uri'
];
foreach ($tables as $table=>$column) {
    $sql   = "select bundle, entity_id from $table where $column like ?";
    $query = $db->query($sql, [$pattern]);
    $result = $query->fetchAll();
    foreach ($result as $row) {
        echo "{$row->bundle} {$row->entity_id}\n";
    }
}
