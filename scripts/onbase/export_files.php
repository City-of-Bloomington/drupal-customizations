<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
const PERMISSION = 0755;

$COLUMNS = ['Type', 'Format', 'Created', 'File'];
$FORMATS = include './formats.php';
$conf    = include './config.php';
$pdo     = new PDO($conf['db'], $conf['user'], $conf['pass']);

$sql     = "select f.filename, f.uri, f.filemime, f.created, f.filesize
            from (select min(fid)      as fid,
                         min(filename) as filename,
                         min(uri)      as uri,
                         min(filemime) as filemime,
                         min(created)  as created,
                         filesize
                  from file_managed
                  group by filesize) f
            left join media__field_image i on f.fid=i.field_image_target_id
            where i.field_image_target_id is null";
$query   = $pdo->prepare($sql);
$query->execute();
$result  = $query->fetchAll(\PDO::FETCH_ASSOC);

if (!is_dir('./files')) { mkdir('./files', PERMISSION); }
$out     = fopen('./files/files.csv', 'w');
fwrite($out, csvLine($COLUMNS));

foreach ($result as $r) {
    $drupal_file = str_replace('public://', '/srv/data/drupal/files/', $r['uri']);
    $extension   = pathinfo($drupal_file, PATHINFO_EXTENSION);
    $export_file = basename($drupal_file);
    $export_path = "./files/$extension";
    $type        = substr($r['filemime'], 0, 5) == 'image' ? 'Image' : 'File';

    if (!is_dir($export_path)) { mkdir($export_path, PERMISSION); }
    copy($drupal_file, "$export_path/$export_file");

    fwrite($out, csvLine([
        $type,
        $FORMATS[$r['filemime']],
        date('c', (int)$r['created']),
        "$export_path/$export_file"
    ]));
}


function csvLine(array $array): string
{
    $line = [];
    foreach ($array as $i) {
        $line[] = '"'.str_replace(['"', "\n"], ['', ' '], $i).'"';
    }
    return implode(',', $line)."\n";
}
