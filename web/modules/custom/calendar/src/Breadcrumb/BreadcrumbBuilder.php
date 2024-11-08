<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0 GNU/GPL2, see LICENSE
 */
namespace Drupal\calendar\Breadcrumb;
use Drupal\calendar\GoogleGateway;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;
use Drupal\Core\Url;

class BreadcrumbBuilder implements BreadcrumbBuilderInterface
{
    public function applies(RouteMatchInterface $route_match)
    {
        $name = explode('.', $route_match->getRouteName());
        return $name[0] == 'calendar';
    }

    public function build(RouteMatchInterface $route_match): Breadcrumb
    {
        $route      = $route_match->getRouteName();
        $breadcrumb = new Breadcrumb();
        $breadcrumb->addCacheContexts(['url']);
        $breadcrumb->addLink(Link::createFromRoute('Home', '<front>'));

        $config  = \Drupal::config('calendar.settings');
        $nids    = explode(',', $config->get('calendar_breadcrumb'));
        $nodes   = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
        foreach ($nodes as $n) {
            $breadcrumb->addLink(Link::createFromRoute($n->title->value, 'entity.node.canonical', ['node'=>$n->nid->value]));
        }
        if ($route == 'calendar.event_view') {
            try {
                $calendar_id = $route_match->getParameter('calendar_id');
                $event_id    = $route_match->getParameter(   'event_id');
                $event       = GoogleGateway::event($calendar_id, $event_id);
                $uri         = "https://calendar.google.com/calendar/embed?src={$event->organizer->email}&ctz=America/New_York";
                $url         = Url::fromUri($uri, ['absolute' => true, 'https' => true]);
                $breadcrumb->addLink(Link::fromTextAndUrl($event->organizer->displayName, $url));
            }
            catch (\Exception $e) { }
        }

        return $breadcrumb;
    }
}
