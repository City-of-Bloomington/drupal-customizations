<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0 GNU/GPL2, see LICENSE
 *
 * This file is part of the Google Calendar drupal module.
 *
 * The calendar module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * The calendar module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the calendar module.  If not, see <https://www.gnu.org/licenses/old-licenses/gpl-2.0/>.
 */
namespace Drupal\calendar\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

use Drupal\calendar\GoogleGateway;
use Drupal\calendar\Plugin\Block\EventsBlock;

/**
 * Plugin implementation of the Google Calendar formatter.
 *
 * @FieldFormatter(
 *   id = "calendar_eventslist",
 *   label = "Events List",
 *   field_types = {
 *     "calendar_calendar"
 *   }
 * )
 */
class EventsList extends FormatterBase
{
    public static function defaultSettings()
    {
        return [
           'numdays'   => EventsBlock::DEFAULT_NUMDAYS,
           'maxevents' => EventsBlock::DEFAULT_MAXEVENTS
        ] + parent::defaultSettings();
    }

    public function settingsForm(array $form, FormStateInterface $form_state)
    {
        return [
            'numdays'   => [
                '#type'          => 'number',
                '#title'         => 'Number of future days to request from Google',
                '#default_value' => $this->getSettings('numdays'),
                '#required'      => true,
                '#min'           => 1
            ],
            'maxevents' => [
                '#type'          => 'number',
                '#title'         => 'Maximum number of events to show',
                '#default_value' => $this->getSettings('maxevents'),
                '#required'      => true,
                '#min'           => 1
            ]
        ];
    }

    public function settingsSummary()
    {
        return [
            "Number of days: {$this->getSetting('numdays')}",
            "Max Events: {$this->getSetting('maxevents')}"
        ];
    }

    public function viewElements(FieldItemListInterface $items, $lang)
    {
        $numdays   = $this->getSetting('numdays'  );
        $maxevents = $this->getSetting('maxevents');

        $start = new \DateTime();
        $end   = new \DateTime();
        $end->add(new \DateInterval("P{$numdays}D"));

        $elements = [];
        foreach ($items as $i=>$item) {
            $calendarId  = trim($item->calendarId);
            if ($calendarId) {
                $events  = GoogleGateway::limitEvents(GoogleGateway::events($calendarId, $start, $end), $maxevents);
                $elements[$i] = [
                    '#theme'      => 'calendar_events',
                    '#events'     => $events,
                    '#calendarId' => $item->calendarId
                ];
            }
        }
        return $elements;
    }
}
