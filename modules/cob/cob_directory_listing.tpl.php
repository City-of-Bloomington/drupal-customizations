<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 * @param $department JSON object for the department data
 */
?>

<div class="cob-main-container cob-directory">
    <?php
        echo renderDepartment($department);

        function renderDepartment($d)
        {
            $fields            = ['address', 'city', 'state', 'zip', 'email'];
            $phoneNumberFields = ['office', 'fax', 'cell', 'other', 'pager' ];

            $name = l($d['name'], 'directory/departments'.$d['path']);
            $html = "<section><h2>$name</h2>";

            foreach ($fields as $f) {
                $$f = check_plain($d[$f]);
            }
            $html.= "
            <div><address>$address\n$city $state $zip</address></div>
            <div><a href=\"mailto:$email\">$email</a></div>
            <table>
            ";
            foreach ($phoneNumberFields as $label) {
                $number = check_plain($d[$label]);
                if ($number) {
                    $html.= "<tr><th>$label</th><td>$number</td></tr>";
                }
            }
            $html.= "
            </table>
            ";

            if (!empty($d['people'])) {
                $html.= "
                <table>
                    <thead>
                        <tr><th scope=\"col\">name</th>
                            <th scope=\"col\">title</th>
                            <th scope=\"col\">phone</th>
                            <th scope=\"col\">email</th>
                        </tr>
                    </thead>
                    <tbody>
                ";
                foreach ($d['people'] as $p) {
                    $name =    !empty($p['displayname'])
                        ? check_plain($p['displayname'])
                        : check_plain("$p[firstname] $p[lastname]");

                    $title = check_plain($p['title']);

                    $phone = '';
                    foreach ($phoneNumberFields as $f) {
                        if (!empty($p[$f])) {
                            $phone = $p[$f];
                            break;
                        }
                    }
                    $email = '';
                    if (!empty($p['email'])) {
                        $email = check_plain($p['email']);
                        $email = "<a href=\"mailto:$email\">$email</a>";
                    }
                    $html.= "
                    <tr><td>$name</td>
                        <td>$title</td>
                        <td class=\"ext-number\">$phone</td>
                        <td>$email</td>
                    </tr>
                    ";
                }
                $html.= "
                    </tbody>
                </table>
                ";
            }
            $html.= "</section>";

            if (  !empty($d['departments'])) {
                foreach ($d['departments'] as $child) {
                    $html.= renderDepartment($child);
                }
            }

            return $html;
        }
    ?>
</div>
