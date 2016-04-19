<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
/**
 * Displays the list of department nodes and their contact info
 */
function _cob_directory()
{
    $d = node_load_multiple([], ['type'=>'department']);

    usort($d, function ($a, $b) {
        if ($a->title == $b->title) { return 0; }
        return ($a->title < $b->title) ? -1 : 1;
    });

    return theme('cob_directory', ['departments'=>$d]);
}

/**
 * Renders contact information for a department, and all nested departments
 *
 * @param StdClass $vars['department'] JSON data for department
 */
function _cob_directory_departmentContactInfo($vars)
{
    $deptContactFields = ['office', 'address', 'email'];

    $html = "<h2>{$vars['department']->name}</h2><dl>";
    foreach ($deptContactFields as $f) {
        if (!empty($vars['department']->$f)) {
            $key   = ucfirst($f);
            $value = check_plain($vars['department']->$f);
            $html.= "<dt>$key</dt><dd>$value</dd>";
        }
    }
    $html.= '</dl>';

    if (isset(   $vars['department']->departments)) {
        foreach ($vars['department']->departments as $d) {
            $html.= _cob_directory_departmentContactInfo(['department'=>$d]);
        }
    }
    return $html;
}

/**
 * Renders contact information for a department's staff, and all nested departments
 *
 * @param StdClass $vars['department']  JSON data for a department
 */
function _cob_directory_peopleContactInfo($vars)
{
    static $previousDepartments = [];

    $html = '';
    if (!in_array(   $vars['department']->name, $previousDepartments)) {
        if (isset(   $vars['department']->people)) {
            foreach ($vars['department']->people as $p) {
                $name = $p->displayname ? $p->displayname : "{$p->firstname} {$p->lastname}";
                $html.= "
                <tr><td>$name <span class=\"cob-directory-title\">{$p->title}</span></td>
                    <td><a href=\"mailto:{$p->email}\">{$p->email}</a></td>
                    <td>{$p->pager}</td>
                </tr>
                ";
            }
        }
        $previousDepartments[] = $vars['department']->name;
    }

    if (isset(   $vars['department']->departments)) {
        foreach ($vars['department']->departments as $d) {
            $html.= _cob_directory_peopleContactInfo(['department'=>$d]);
        }
    }
    return $html;
}

/**
 * Returns contact information for a department
 *
 * We store the official contact information in Active Directory
 * This function requests data from the Directory web application's
 * restful web service.
 *
 * @param string $dn The DN for the department
 * @return stdClass The JSON object from the response
 */
function cob_department_info($dn)
{
    $DIRECTORY = variable_get('cob_directory_uri');
    $url = $DIRECTORY.'/departments/view?format=json;dn='.urlencode($dn);
    $json = cob_http_get($url);
    return json_decode($json);
}

function cob_person_info($username)
{
    $DIRECTORY = variable_get('cob_directory_uri');
    $url = $DIRECTORY.'/people/view?format=json;username='.$username;
    $json = cob_http_get($url);
    return json_decode($json);
}
