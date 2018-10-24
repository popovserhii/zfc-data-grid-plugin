<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Popov
 * @package Popov_ZfcDataGridPlugin
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcDataGridPlugin\Column\Attribute;

use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;
use ZfcDatagrid\Column\AbstractColumn;
use ZfcDatagrid\Column\Formatter\Link;

class LinkAttribute implements AttributeInterface
{
    /**
     * @var ColumnFactory
     */
    protected $columnFactory;

    public function setColumnFactory($columnFactory)
    {
        $this->columnFactory = $columnFactory;
    }

    /**
     * Prepare "link" attribute value based on special array configuration.
     * Config key:
     *      href - not changed link path
     *      placeholder_column - Column object or column id for get placeholder value
     *
     * @param Link $formatter
     * @param $params
     * @return string
     */
    public function prepare($formatter, $params)
    {
        if (!is_array($params)) {
            return $params;
        }

        $marks = [];
        $placeholders = is_object($params['placeholder_column'])
            ? [$params['placeholder_column']]
            : (array) $params['placeholder_column'];

        foreach ($placeholders as $placeholder) {
            if (is_string($placeholder) && ($column = $this->columnFactory->getColumn($placeholder))) {
                $marks[] = $formatter->getColumnValuePlaceholder($column);
            } elseif (($placeholder instanceof AbstractColumn)) {
                $marks[] = $formatter->getColumnValuePlaceholder($placeholder);
            } else {
                $marks[] = ':' . $placeholder . ':';

                // When "placeholder_column" is string, such as "item_code",
                // we don't know if column was already added or will be added during next calls.
                // That's why we postpone column register in getColumnValuePlaceholder
                // and add this task as a closure for next check.
                $this->columnFactory->addDeferredPreparation(function(AbstractColumn $column) use ($formatter, $placeholders) {
                    foreach ($placeholders as $placeholder) {
                        ($column->getUniqueId() === $placeholder)
                            ? $formatter->getColumnValuePlaceholder($column)
                            : false;
                    }
                });
            }
        }

        $href = sprintf($params['href'], ...$marks);

        return $href;
    }
}