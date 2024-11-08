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
namespace Drupal\calendar\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Provides a field type for linking to a Google Calendar.
 *
 * @FieldType(
 *   id = "calendar_calendar",
 *   label = "Google Calendar",
 *   default_formatter = "calendar_eventslist",
 *   default_widget = "calendar_calendarfield",
 * )
 */
class CalendarItem extends FieldItemBase
{
    public static function schema(FieldStorageDefinitionInterface $field_definition)
    {
        return [
            'columns' => [
                'calendarId' => [
                    'type'     => 'varchar',
                    'length'   => 128,
                    'not null' => true
                ]
            ]
        ];
    }

    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition)
    {
        $properties['calendarId'] = DataDefinition::create('string')->setLabel('Calendar ID');
        return $properties;
    }

    public function isEmpty()
    {
        $value = $this->get('calendarId')->getValue();
        return $value === null || $value === '';
    }
}
