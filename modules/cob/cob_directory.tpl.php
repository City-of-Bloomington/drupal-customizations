<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param $departments The raw JSON response
 */
$fields = ['office', 'fax', 'address', 'email'];
foreach ($departments as $d) {
    $name = l($d->name, 'directory/departments'.$d->path);

    echo "<table><caption>$name</caption>";
    foreach ($fields as $f) {
        if (!empty($d->$f)) {
            $value = check_plain($d->$f);
            echo "<tr><th>$f</th><td>$value</td></tr>";
        }
    }
    echo "</table>";
}