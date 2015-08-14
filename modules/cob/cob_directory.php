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
    $url = 'http://apps.bloomington.in.gov/directory/departments/view?format=json;dn='.urlencode($dn);
    $json = cob_http_get($url);
    return json_decode($json);
}
