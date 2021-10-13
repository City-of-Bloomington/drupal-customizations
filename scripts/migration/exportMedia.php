<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
define('SITE_HOME', $_SERVER['SITE_HOME']);
include SITE_HOME.'/settings.php';

$config = $databases['default']['default'];
$pdo    = new \PDO("mysql:dbname=$config[database];host=$config[host]", $config['username'], $config['password']);
$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

if (!is_dir(SITE_HOME.'/backup')) { mkdir(SITE_HOME.'/backup', 0775, true); }

$sql = [
  'cover_image' => "select c.bundle,
                           c.entity_id as node_id,
                           m.entity_id as media_id,
                           f.fid,
                           p.alias,
                           f.uri,
                           d.thumbnail__title as title,
                           m.field_image_alt  as alt
                    from node__field_cover_image c
                    join media__field_image      m on m.entity_id=c.field_cover_image_target_id
                    left join media_field_data   d on d.mid=m.field_image_target_id
                    join file_managed            f on f.fid=m.field_image_target_id
                    left join path_alias         p on p.path=concat('/node/', c.entity_id)",
  'content_image' => "select c.bundle,
                             c.entity_id as node_id,
                             m.entity_id as media_id,
                             f.fid,
                             p.alias,
                             f.uri,
                             d.thumbnail__title as title,
                             m.field_image_alt  as alt
                      from node__field_content_image c
                      join media__field_image        m on m.entity_id=c.field_content_image_target_id
                      left join media_field_data     d on d.mid=c.field_content_image_target_id
                      join file_managed              f on f.fid=m.field_image_target_id
                      left join path_alias           p on p.path=concat('/node/', c.entity_id)",
  'image_browser' => "select i.bundle,
                             i.entity_id as node_id,
                             f.fid,
                             p.alias,
                             f.uri,
                             i.field_image_browser_title as title,
                             i.field_image_browser_alt   as alt
                      from node__field_image_browser i
                      join file_managed              f on f.fid=i.field_image_browser_target_id
                      left join path_alias           p on p.path=concat('/node/', i.entity_id)"
];

$fields = ['cover_image', 'content_image', 'image_browser'];
foreach ($fields as $type) {
  $backup_dir = SITE_HOME."/backup/$type";
  $csv =  fopen(SITE_HOME."/backup/$type.csv", 'w');

  $result = $pdo->query($sql[$type])->fetchAll(\PDO::FETCH_ASSOC);
  fputcsv($csv, array_keys($result[0]));
  reset($result);

  foreach ($result as $row) {
      fputcsv($csv, $row);

      $uri  = substr($row['uri'], 9);
      $file = SITE_HOME."/files/$uri";
      $dir  = dirname("$backup_dir/$uri");

      if (!is_dir($dir)) { mkdir($dir, 0775, true); }
      copy($file, "$backup_dir/$uri");
  }
}

