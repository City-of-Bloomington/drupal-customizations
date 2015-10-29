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
<div class="cob-main-container cob-directory">
    <?php
        $fields = ['office'/*, 'fax'*/, 'address', 'email'];
        foreach ($departments as $d) {
            if (!empty($d->field_directory_dn['und'][0]['value'])) {
                echo '<section class="cob-directory-section">';
                    $name = l($d->title, 'node/'.$d->nid);
                    echo '<h2>'.$name.'</h2>';

                    echo '<div class="cob-directory-deptInfo">';
                        echo '<dl>';

                            $info = cob_department_info($d->field_directory_dn['und'][0]['value']);
                            foreach ($fields as $f) {
                                if (!empty($info->$f)) {
                                    $key   = ucfirst($f);
                                    $value = check_plain($info->$f);
                                    echo "<dt>$key</dt><dd>$value</dd>";
                                }
                            }
                        echo '</dl>';
                    echo '</div>';
                ?>
                    <div class="cob-directory-deptStaff">
                        <table>
                            <thead>
                                <tr>
                                    <td>Name and title</td>
                                    <td>Email address</td>
                                    <td>Phone Number</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>First Last <span class="cob-directory-title">Director</span></td>
                                    <td><a href="mailto:lastf@bloomington.in.gov">lastf@bloomington.in.gov</a></td>
                                    <td>812-349-3454</td>
                                </tr>
                                <tr>
                                    <td>First Last <span class="cob-directory-title">Director</span></td>
                                    <td><a href="mailto:lastf@bloomington.in.gov">lastf@bloomington.in.gov</a></td>
                                    <td>812-349-3454</td>
                                </tr>
                                <tr>
                                    <td>First Last <span class="cob-directory-title">Director</span></td>
                                    <td><a href="mailto:lastf@bloomington.in.gov">lastf@bloomington.in.gov</a></td>
                                    <td>812-349-3454</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php
                echo '</section>';
            }
        }
    ?>
</div>
