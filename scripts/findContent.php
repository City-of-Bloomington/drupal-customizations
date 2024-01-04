<?php
/**
 * Raw database queries to find content on the site
 *
 * This script must be copied to /srv/sites/drupal before running.
 *
 * This script does not need drush and can be run using php directly:
 * cd /srv/sites/drupal
 * php findContent.php
 *
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
    $sql    = "select b.entity_id,
                      a.alias
               from $table b
               left join path_alias a on a.`path`=concat('/node/', b.entity_id)
               where b.$column like ?";
    $query  = $db->query($sql, [$pattern]);
    $result = $query->fetchAll();
    foreach ($result as $row) {
        echo "https://bloomington.in.gov{$row->alias}\n";
    }
}
