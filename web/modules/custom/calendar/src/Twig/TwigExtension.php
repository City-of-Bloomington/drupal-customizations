<?php
/**
 * @copyright 2017-2024 City of Bloomington, Indiana
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
namespace Drupal\calendar\Twig;

class TwigExtension extends \Twig\Extension\AbstractExtension
{
    public function getName() { return 'calendar.twig_extension'; }

    public function getFilters()
    {
        return [
            new \Twig\TwigFilter('preg_get', [$this, 'get'])
        ];
    }

    public static function get($subject, $regex)
    {
        preg_match($regex, $subject, $matches);
        return isset($matches[0]) ? $matches[0] : null;
    }
}
