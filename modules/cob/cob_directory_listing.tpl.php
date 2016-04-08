<?php
/**
 * Displays the listing for a single department
 *
 * @copyright 2015-2016 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @param StdClass $department JSON object for the department data
 * @param array    $node       The drupal node for the department
 */

$name = l($node->title, 'node/'.$node->nid);
?>
<section class="cob-directory-section">
    <h1><?= $name; ?></h1>
    <div class="cob-directory-deptInfo">
    <?php
        echo theme('cob_directory_departmentContactInfo', ['department'=>$department]);
    ?>
    </div>
    <div class="cob-directory-deptStaff">
        <table>
            <thead>
                <tr><td>Name and title</td>
                    <td>Email address</td>
                    <td>Phone Number</td>
                </tr>
            </thead>
            <tbody>
            <?php
                echo theme('cob_directory_peopleContactInfo', ['department'=>$department]);
            ?>
            </tbody>
        </table>
    </div>
</section>
