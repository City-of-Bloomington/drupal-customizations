<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
/**
 * Drupal Page Callback
 */
function _cob_rectrac_activity($id)
{
    $json = cob_rectrac_activity($id);
    if ($json->$id) {
        return theme('cob_rectrac_activity', ['activity'=>$json->$id]);
    }
}

/**
 * Returns information about a single activity in RecTrac
 *
 * @param int $activity_id
 * @return stdClass The JSON object from the web service
 */
function cob_rectrac_activity($id)
{
    $RECTRAC = variable_get('cob_rectrac_uri');
    $url = $RECTRAC.'/activities?format=json;';

    if (is_numeric($id)) {
        $id  = (int)$id;
        $url.= 'id='.$id;
    }
    else {
        $id = preg_replace('|[^A-Z]|', '', $id);
        $url.= 'type='.$id;
    }
    $json = cob_http_get($url);
    return json_decode($json);
}

/**
 * Returns the RecTrac types associated with the chosen category
 *
 * @param string $category The five character category Type code from RecTrac
 * @return stdClass The JSON object from the response
 */
function cob_rectrac_types($category)
{
    $RECTRAC = variable_get('cob_rectrac_activity');
    $url = "$RECTRAC/types?format=json;category=$category";
    $json = cob_http_get($url);
    return json_decode($json);
}
