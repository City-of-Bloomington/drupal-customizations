<?php
/**
 * @copyright 2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */

/**
 * Lazy loads the google calendar service
 *
 * @return Google_Service_Calendar
 */
function cob_calendar_service()
{
    static $client  = null;
    static $service = null;

    if (!$service) {
        if (!$client) {
            libraries_load('google-api-php-client');

            $json = json_decode(file_get_contents(DRUPAL_ROOT.'/'.conf_path().'/credentials.json'));
            $credentials = new \Google_Auth_AssertionCredentials(
                $json->client_email,
                ['https://www.googleapis.com/auth/calendar.readonly'],
                $json->private_key
            );
            $credentials->sub = variable_get('cob_google_email');

            $client = new \Google_Client();
            $client->setClassConfig('Google_Cache_File', 'directory', DRUPAL_ROOT.'/'.conf_path().'/files/Google_Client');
            $client->setAssertionCredentials($credentials);
            if ($client->getAuth()->isAccessTokenExpired()) {
                $client->getAuth()->refreshTokenWithAssertion();
            }
        }
        $service = new \Google_Service_Calendar($client);
    }
    return $service;
}

/**
 * @see https://developers.google.com/google-apps/calendar/v3/reference/events/list
 * @param string $calendarId
 * @param DateTime $start
 * @param DateTime $end
 * @param boolean $singleEvents
 * @param int $maxResults
 * @return Google_Service_Calendar_EventList
 */
function cob_calendar_events($calendarId, \DateTime $start=null, \DateTime $end=null, $singleEvents=true, $maxResults=null)
{
    $FIELDS = 'description,end,endTimeUnspecified,htmlLink,id,location,'
            . 'originalStartTime,recurrence,recurringEventId,sequence,'
            . 'start,summary,attendees,organizer';

    $opts = [
        'fields'       => "items($FIELDS)",
        'singleEvents' => $singleEvents,
        'maxResults'   => $maxResults
    ];
    if ($singleEvents) { $opts['orderBy'] = 'startTime'; }

    if ($start) { $opts['timeMin'] = $start->format(\DateTime::RFC3339); }
    if ($end  ) { $opts['timeMax'] = $end  ->format(\DateTime::RFC3339); }

    $service = cob_calendar_service();
    $events = $service->events->listEvents($calendarId, $opts);
    return $events;
}

/**
 * @param string $calendarId
 * @return int
 */
function cob_calendar_node_id($calendarId)
{
    $query = new EntityFieldQuery();
    $query->fieldCondition('field_google_calendar_id', 'value', $calendarId, '=');
    $result = $query->execute();

    if (count($result)) {
        return array_keys($result['node'])[0];
    }
}

/**
 * @return string
 */
function cob_calendar_url($calendarId)
{
    return 'https://www.google.com/calendar/embed?mode=AGENDA&src='.$calendarId;
}
