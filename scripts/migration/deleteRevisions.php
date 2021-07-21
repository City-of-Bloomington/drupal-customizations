<?php
/**
 * This script must be run using drush php-script
 *
 * vendor/bin/drush php-script deleteRevisions.php
 *
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 * @see https://drushcommands.com/drush-8x/core/php-script
 */
declare (strict_types=1);

use Drupal\node\Entity\Node;

$nodes   = \Drupal::entityQuery('node')->execute();
$storage = \Drupal::entityTypeManager()->getStorage('node');
foreach ($nodes as $nid) {
  $node     = Node::load($nid);
  $current  = $node->getLoadedRevisionId();
  $versions = $storage->revisionIds($node);

  foreach ($versions as $vid) {
    if ($vid != $current) {
      echo "$nid: $vid - Delete\n";
      $storage->deleteRevision($vid);
    }
    else  { echo "$nid: $vid - Keep\n"; }
  }
}
