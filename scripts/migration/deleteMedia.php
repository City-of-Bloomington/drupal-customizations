<?php
/**
 * This script must be run using drush php-script
 *
 * vendor/bin/drush php-script deleteMedia
 *
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @see https://drushcommands.com/drush-8x/core/php-script
 */
declare (strict_types=1);

$media = \Drupal::entityTypeManager()
       ->getStorage('media')
       ->loadMultiple();

foreach ($media as $m) { $m->delete(); }

$db  = \Drupal::database();
$sql = "select f.fid, u.`count`
        from      file_managed f
        left join file_usage   u on f.fid=u.fid
        where u.`count` is null or u.`count`=0";
$query = $db->query($sql);

foreach ($query as $row) {
  $file  = \Drupal\file\Entity\File::load($row->fid);
  $file->delete();
}
