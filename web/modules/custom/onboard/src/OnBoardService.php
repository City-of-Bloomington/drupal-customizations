<?php
/**
 * @copyright 2017-2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
declare (strict_types=1);
namespace Drupal\onboard;

use Drupal\Core\Site\Settings;

class OnBoardService
{
    public static function getUrl()
    {
        $config  = \Drupal::config('onboard.settings');
        return $config->get('onboard_url');
    }

    private static function doJsonQuery($url)
    {
        $client   = \Drupal::httpClient();
        $response = $client->get($url);
        return json_decode((string)$response->getBody(), true);
    }
    /**
     * @param  array    $query  Search parameters for the committee list
     * @return array            The JSON object
     */
    public static function committee_list(array $query=null): array
    {
        $base_url = self::getUrl();
        if ($base_url) {
            $params = $query ? array_merge(['format' => 'json'], $query) : ['format' => 'json'];
            $url    = $base_url.'/committees?'.http_build_query($params, '', ';');
            return self::doJsonQuery($url);
        }
        return [];
    }

    /**
     * @param  int      $committee_id
     * @return stdClass               The json object
     */
    public static function committee_info($committee_id)
    {
        $url = self::getUrl().'/committees/members?format=json;committee_id='.$committee_id;
        return self::doJsonQuery($url);
    }

    public static function meetings($committee_id, $year=null, \DateTime $start=null, \DateTime $end=null, ?int $limit=0): array
    {
        $params = ['committee_id'=>$committee_id, 'format'=>'json'];
        if ($start) {
            if (!$end) {
                $end = clone $start;
                $end->add(new \DateInterval('P1Y'));
            }
            $params['start'] = $start->format('Y-m-d');
            $params['end'  ] = $end  ->format('Y-m-d');
        }
        else {
            $params['year'] = $year;
        }

        $url      = self::getUrl().'/committees/meetings?'.http_build_query($params);
        $meetings = self::doJsonQuery($url);
        if ($meetings) {
            return $limit ? array_slice($meetings, 0, $limit) : $meetings;
        }
        return [];
    }

    public static function meetingFile_years($committee_id)
    {
        $url = self::getUrl()."/meetingFiles/years?format=json;committee_id=$committee_id";
        return self::doJsonQuery($url);
    }

    /**
     * @param array $params  Parameters for legislation query
     */
    public static function legislation_list(array $params)
    {
        $params['format'] = 'json';
        $url = self::getUrl()."/legislation?".http_build_query($params);
        return self::doJsonQuery($url);
    }

    public static function legislation_years($committee_id, $type)
    {
        $url = self::getUrl()."/legislation/years?format=json;committee_id=$committee_id;type=$type";
        return self::doJsonQuery($url);
    }

    public static function legislation_types()
    {
        static $types = null;
        if (!$types) {
            $url   = self::getUrl()."/legislationTypes?format=json";
            $types = self::doJsonQuery($url);
        }
        return $types;
    }

    public static function reports($committee_id)
    {
        $url = self::getUrl()."/reports?format=json;committee_id=$committee_id";
        return self::doJsonQuery($url);
    }

    /**
     * @param  $type string
     * @return       boolean
     */
    public static function typeExists($type)
    {
        foreach (self::legislation_types() as $t) {
            if ($t['name'] == $type) {
                return true;
            }
        }
        return false;
    }
}
