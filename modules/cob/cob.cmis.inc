<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

 /**
 * Instantantiates the CMISService using variables from the admin form
 *
 * @return CMISService
 */
function _cob_get_cmis()
{
    static $cmis;

    if (!$cmis) {
        libraries_load('chemistry');

        $cmis = new CMISService(
            variable_get('cob_cmis_url'),
            variable_get('cob_cmis_username'),
            variable_get('cob_cmis_password'),
            variable_get('cob_cmis_repositoryId')
        );
        $cmis->succinct = true;
    }
    return $cmis;
}

function cob_cmis_download($node, $objectId)
{
    $cmis = _cob_get_cmis();
    $o = $cmis->getObject($objectId);

    $mime = $o->succinctProperties->{'cmis:contentStreamMimeType'};
    $file = $o->succinctProperties->{'cmis:contentStreamFileName'};
    $size = $o->succinctProperties->{'cmis:contentStreamLength'};

    header("Content-Type: $mime");
    header("Content-Disposition: attachment; filename=\"$file\"");
    header("Content-Length: $size");
    echo $cmis->getContentStream($objectId);
}
