<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);
namespace Drupal\calendar\Controller;

use Drupal\calendar\GoogleGateway;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalendarController extends ControllerBase
{
    public function event_info(string $calendar_id, string $event_id): array
    {
        try { $event = GoogleGateway::event($calendar_id, $event_id); }
        catch (\Exception $e) { throw new NotFoundHttpException(); }

        return [
            '#theme' => 'calendar_event',
            '#event' => $event
        ];
    }
}
