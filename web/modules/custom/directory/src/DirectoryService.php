<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\directory;

use Drupal\Core\Site\Settings;

class DirectoryService
{
    private static function doJsonQuery($url)
    {
        $client   = \Drupal::httpClient();
        try {
            $response = $client->get($url);
            return json_decode($response->getBody(), true);
        }
        catch (\Exception $e) {
            return [];
        }
    }
    /**
     * Returns contact information for a department
     *
     * We store the official contact information in Active Directory
     * This function requests data from the Directory web application's
     * restful web service.
     *
     * @param  string   $dn The DN for the department
     * @return stdClass     The JSON object from the response
     */
    public static function department_info($dn)
    {
        $config    = \Drupal::config('directory.settings');
        $DIRECTORY = $config->get('directory_url');
        $url       = $DIRECTORY.'/departments/view?promoted=1;format=json;dn='.urlencode($dn);
        return self::doJsonQuery($url);
    }

    /**
     * @param  string   $username Username in ActiveDirectory
     * @return stdClass           The JSON object from the response
     */
    function person_info($username)
    {
        $config    = \Drupal::config('directory.settings');
        $DIRECTORY = $config->get('directory_url');
        $url       = $DIRECTORY.'/people/view?format=json;username='.$username;
        return self::doJsonQuery($url);
    }
}
