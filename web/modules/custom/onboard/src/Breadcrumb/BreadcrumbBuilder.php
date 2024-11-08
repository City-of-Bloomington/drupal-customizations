<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
declare (strict_types=1);
namespace Drupal\onboard\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;

class BreadcrumbBuilder implements BreadcrumbBuilderInterface
{
    public function applies(RouteMatchInterface $route_match)
    {
        $name = explode('.', $route_match->getRouteName());
        return $name[0] == 'onboard' && $name[1]!='settings';
    }

    public function build(RouteMatchInterface $route_match)
    {
        $name = explode('.', $route_match->getRouteName());

        $node   = $route_match->getParameter('node');
        $nid    = $node->get('nid')->value;
        $params = ['node' => $nid];

        $breadcrumb = new Breadcrumb();
        $breadcrumb->addCacheContexts(['url']);
        $breadcrumb->addLink(Link::createFromRoute('Home', '<front>'));
        $breadcrumb->addLink(Link::createFromRoute($node->title->value, 'entity.node.canonical', $params));

        if (substr($name[1], 0, 11) == 'legislation') {
            $breadcrumb->addLink(Link::createFromRoute('Legislation', "onboard.legislationTypes.node-$nid", $params));
            $type   = $route_match->getParameter('type'  );
            $year   = $route_match->getParameter('year'  );
            $number = $route_match->getParameter('number');
            if ($year) {
                $params['type'] = $type;
                $breadcrumb->addLink(Link::createFromRoute($type, "onboard.legislationYears.node-$nid", $params));
            }
            if ($number) {
                $params['year'] = $year;
                $breadcrumb->addLink(Link::createFromRoute($year, "onboard.legislationList.node-$nid", $params));
            }
        }
        else {
            $year = $route_match->getParameter('year');
            if ($year) {
                $breadcrumb->addLink(Link::createFromRoute('Meetings', "onboard.meetingYears.node-$nid", $params));
            }
        }

        return $breadcrumb;
    }
}
