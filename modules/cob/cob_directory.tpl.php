<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param $departments Drupal Department nodes
 */
 ?>
<div class="cob-pageOverview">
    <div class="cob-pageOverview-container">
        <div>
            Intro text.
        </div>
    </div>
</div>
<div class="cob-main-container">
    <?php
        $fields = ['office', 'fax', 'address', 'email'];
        foreach ($departments as $d) {
            if (!empty($d->field_directory_dn['und'][0]['value'])) {
                $name = l($d->title, 'node/'.$d->nid);
                echo "<table><caption>$name</caption>";

                $info = cob_department_info($d->field_directory_dn['und'][0]['value']);
                foreach ($fields as $f) {
                    if (!empty($info->$f)) {
                        $value = check_plain($info->$f);
                        echo "<tr><th>$f</th><td>$value</td></tr>";
                    }
                }
                echo "</table>";
            }
        }
    ?>
</div>
