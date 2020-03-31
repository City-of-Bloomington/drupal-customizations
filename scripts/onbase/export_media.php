<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
$COLUMNS = ['Type', 'Format', 'Created', 'Title', 'Alt Text', 'File'];
$TYPES   = [
    'cover_image'   => 'Cover Image',
    'content_image' => 'Image'
];
$FORMATS = include './formats.php';
$conf    = include './config.php';
$pdo     = new PDO($conf['db'], $conf['user'], $conf['pass']);
foreach ($TYPES as $bundle => $type) {
    $dir = "./files/$type";
    if (!is_dir($dir)) { mkdir($dir, 0766, true); }

    $out = fopen("./files/$type.csv", 'w');
    $sql = "select f.filename, f.uri, f.filemime, f.created, f.filesize,
                   i.field_image_title, i.field_image_alt
            from (select min(fid)      as fid,
                         min(filename) as filename,
                         min(uri)      as uri,
                         min(filemime) as filemime,
                         min(created)  as created,
                         filesize
                  from file_managed
                  group by filesize) f
            join media__field_image  i on f.fid=i.field_image_target_id
            where bundle=?
            limit 20";
    $query   = $pdo->prepare($sql);
    $query->execute([$bundle]);
    $result  = $query->fetchAll(\PDO::FETCH_ASSOC);

    fwrite($out, csvLine($COLUMNS));
    foreach ($result as $r) {
        $drupal_file = str_replace('public://', '/srv/data/drupal/files/', $r['uri']);
        $export_file = basename($drupal_file);
        $export_path = str_replace('./files/', '', $dir);

        copy($drupal_file, "$dir/$export_file");

        fwrite($out, csvLine([
            $type,
            $FORMATS[$r['filemime']],
            date('c', (int)$r['created']),
            $r['field_image_title'],
            $r['field_image_alt'  ],
            "$export_path/$export_file"
        ]));
    }
}

function csvLine(array $array): string
{
    $line = [];
    foreach ($array as $i) {
        $line[] = '"'.str_replace(['"', "\n"], ['', ' '], $i).'"';
    }
    return implode(',', $line)."\n";
}
