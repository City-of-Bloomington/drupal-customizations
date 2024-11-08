<?php
/**
 * @copyright 2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
declare (strict_types=1);

namespace Drupal\archive\Plugin\search_api\processor;

use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;
use Drupal\search_api\Processor\ProcessorProperty;

/**
 * Adds the item's URL to the indexed data.
 *
 * @SearchApiProcessor(
 *   id          = "archive_year",
 *   label       = "Created Year",
 *   description = "Adds a Year field by reading the created date",
 *   stages = {
 *     "add_properties" = 0,
 *   },
 *   locked = true,
 *   hidden = true,
 * )
 */
class CreatedYearPlugin extends ProcessorPluginBase
{
    public const PROP_ID = 'archive_year';

    public function getPropertyDefinitions(DatasourceInterface $datasource=null)
    {
        $prop = [];
        if (!$datasource) {
            $prop[self::PROP_ID] = new ProcessorProperty([
                'label'        => 'Created Year',
                'description'  => 'Year of created date',
                'type'         => 'integer',
                'is_list'      => false,
                'processor_id' => $this->getPluginId()
            ]);
        }
        return $prop;
    }

    public function addFieldValues(ItemInterface $item)
    {
        $values = $item->getField('date')->getValues();
        if ($values && !empty($values[0])) {
            $fields = $this->getFieldsHelper()->filterForPropertyPath($item->getFields(), NULL, self::PROP_ID);
            foreach ($fields as $f) { $f->addValue(date('Y', $values[0])); }
        }
    }
}
