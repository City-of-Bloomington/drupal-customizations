<?php
/**
 * @copyright 2015-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param array $departments Drupal Department nodes
 */
 ?>
<div class="cob-pageOverview">
    <div class="cob-pageOverview-container">
    </div>
</div>
<div class="cob-main-container cob-directory">
<?php
    foreach ($departments as $d) {
        if (!empty($d->field_directory_dn['und'][0]['value'])) {
            $department = cob_department_info($d->field_directory_dn['und'][0]['value']);
            echo theme('cob_directory_listing', ['department'=>$department, 'node'=>$d]);
        }
    }
?>
</div>
