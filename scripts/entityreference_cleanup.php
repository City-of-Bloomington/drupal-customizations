<?php
/**
 * Deletes all entityreference links to nodes that have been deleted
 *
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
$pdo = new PDO('mysql:host=localhost;dbname=drupal', 'username', 'password');

$sql = "select * from field_config where type='entityreference'";
$results = $pdo->query($sql)->fetchAll();
foreach ($results as $row) {
	$field = unserialize($row['data']);
	foreach ($field['storage']['details']['sql'] as $r) {
		foreach ($r as $table=>$columns) {
			$sql = "delete $table from $table
					left join node on $table.$columns[target_id]=node.nid
					where node.nid is null";
			$pdo->query($sql);
		}
	}
}
