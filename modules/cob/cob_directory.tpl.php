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
    $deptContactFields = ['office', 'address', 'email'];
    foreach ($departments as $d) {
        if (!empty($d->field_directory_dn['und'][0]['value'])) {
            $name = l($d->title, 'node/'.$d->nid);
            echo "
            <section class=\"cob-directory-section\">
                <h1>$name</h1>
                <div class=\"cob-directory-deptInfo\">
                    <dl>
            ";
                    $info = cob_department_info($d->field_directory_dn['und'][0]['value']);
                    foreach ($deptContactFields as $f) {
                        if (!empty($info->$f)) {
                            $key   = ucfirst($f);
                            $value = check_plain($info->$f);
                            echo "<dt>$key</dt><dd>$value</dd>";
                        }
                    }
            echo "
                    </dl>
                </div>
                <div class=\"cob-directory-deptStaff\">
                    <table>
                        <thead>
                            <tr><td>Name and title</td>
                                <td>Email address</td>
                                <td>Phone Number</td>
                            </tr>
                        </thead>
                        <tbody>
            ";
                    if (isset($info->people)) {
                        foreach ($info->people as $p) {
                            $name = $p->displayname ? $p->displayname : "{$p->firstname} {$p->lastname}";
                            echo "
                            <tr><td>$name <span class=\"cob-directory-title\">{$p->title}</span></td>
                                <td><a href=\"mailto:{$p->email}\">{$p->email}</a></td>
                                <td>{$p->office}</td>
                            </tr>
                            ";
                        }
                    }
            echo "
                        </tbody>
                    </table>
                </div>
            </section>
            ";
        }
    }
?>
</div>
