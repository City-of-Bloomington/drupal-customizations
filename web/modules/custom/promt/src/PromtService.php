<?php
/**
 * @copyright 2017-2020 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Drupal\promt;

use Drupal\Core\Site\Settings;

class PromtService
{
    /**
     * Maps Drupal fields to PROMT query parameters
     *
     * Drupal fieldnames are the keys.
     * Promt fieldnames are the values
     * $fields[ drupal_field => promt_field ]
     */
    public static $fields = [
        'category_id' => 'category_id',
        'location_id' => 'location_id',
        'ageGroup'    => 'age'
    ];

    private static function getUrl()
    {
        $config = \Drupal::config('promt.settings');
        return $config->get('promt_url');
    }

    /**
     * @param  string $url
     * @return array        The JSON data
     */
    private static function doJsonQuery($url)
    {
        $client   = \Drupal::httpClient();
        $response = $client->request('GET', $url);
        $json     = json_decode($response->getBody(), true);
        if (!$json) {
            $err = json_last_error();
            if ($err !== JSON_ERROR_NONE) {
                throw new \Exception(json_last_error_msg());
            }
        }
        return $json;
    }

    public static function programs(array $fields=null): array
    {
        $search = [];
        foreach (self::$fields as $drupalField => $promtField) {
            if (!empty($fields[$drupalField])) {
                $search[$promtField] = $fields[$drupalField];
            }
        }
        $params   = $search ? '?'.http_build_query($search) : '';
        $url      = self::getUrl().'/PromtService'.$params;
        $programs = self::doJsonQuery($url);
        foreach ($programs as $i=>$p) {
            if (empty($p['can_publish']) || !$p['can_publish']) {
                unset($programs[$i]);
            }
        }
        return $programs;
    }

    /**
     * Returns information for a single program
     */
    public static function program(int $id): ?array
    {
        $url = self::getUrl().'/PromtService?program_id='.$id;
        $program = self::doJsonQuery($url);

        // Figure out a RecTrac program_id, if we can
        // RecTrac sessions are named as $id-$letter.
        // So, if we convert the session code to an int, we should be left
        // with the RecTrac ID as a number.
        if (!empty($program['sessions'][0]['code'])) {
            $rectrac_id = (int)$program['sessions'][0]['code'];
            if ($rectrac_id) {
                $program['rectrac_id'] = $rectrac_id;
            }
        }

        return !empty($program['can_publish']) && $program['can_publish']
            ? $program
            : null;
    }

    public static function locations()
    {
        $url = self::getUrl().'/PromtService?list_type=locations';
        return self::doJsonQuery($url);
    }

    public static function categories()
    {
        $url = self::getUrl().'/PromtService?list_type=categories';
        return self::doJsonQuery($url);
    }

    public static function ageGroups()
    {
        $url = self::getUrl().'/PromtService?list_type=age_groups';
        return self::doJsonQuery($url);
    }
}
