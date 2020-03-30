<?php
/**
 * @copyright 2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
$COLUMNS = ['Type', 'Format', 'Title', 'Alt', 'File'];
$TYPES   = [
    'cover_image'   => 'Cover Image',
    'content_image' => 'Image'
];

$FORMATS = [
    'application/pdf'                                                           => 16,
    'audio/mpeg'                                                                => 9999,
    'image/jpeg'                                                                =>  2,
    'image/png'                                                                 =>  2,
    'image/gif'                                                                 =>  2,
    'application/msword'                                                        => 12,
    'application/vnd.ms-excel'                                                  => 13,
    'application/vnd.ms-powerpoint'                                             => 14,
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 12,
    'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 14,
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 13
];

foreach ($TYPES as $bundle => $type) {
    $dir   = "./files/$type";
    if (!is_dir($dir)) { mkdir($dir, 0766, true); }

    $out   = fopen("./files/$type.csv", 'w');
    $conf  = include './config.inc';
    $pdo   = new PDO($conf['db'], $conf['user'], $conf['pass']);
    $sql   = "select i.entity_id,
                    i.field_image_alt,
                    i.field_image_title,
                    f.uuid,
                    f.filename,
                    f.uri,
                    f.filemime,
                    f.filesize,
                    f.created,
                    f.changed
            from media__field_image i
            join file_managed       f on f.fid=i.field_image_target_id
            where bundle=?
            limit 20";
    $query = $pdo->prepare($sql);
    $query->execute([$bundle]);
    $result = $query->fetchAll(\PDO::FETCH_ASSOC);

    fwrite($out, csvLine($COLUMNS));
    foreach ($result as $r) {
        $drupal_file = str_replace('public://', '/srv/data/drupal/files/', $r['uri']);
        $export_file = basename($drupal_file);
        $export_path = str_replace('./files/', '', $dir);

        copy($drupal_file, "$dir/$export_file");

        fwrite($out, csvLine([
            $type,
            $FORMATS[$r['filemime']],
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

function quote(string $string): string
{
    return '"'.str_replace(['"', "\n"], ['', ' ']).'"';
}
