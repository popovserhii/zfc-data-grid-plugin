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

use PhpOffice\PhpSpreadsheet\Style\Style;
use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;
use ZfcDatagrid\Column\AbstractColumn;
use ZfcDatagrid\Column\Style\Color;

class ByValueAttribute implements AttributeInterface
{
    /**
     * @var ColumnFactory
     */
    protected $columnFactory;

    public function setColumnFactory($columnFactory)
    {
        $this->columnFactory = $columnFactory;
    }

    public function prepare($style, $params)
    {
        $groups = is_array($params[0]) ? $params : [$params];
        $placeholders = [];
        foreach ($groups as $params) {
            foreach ($params as $i => $param) {
                if ($this->columnFactory->isPlaceholder($param) && ($column = $this->columnFactory->getColumn($param))) {
                    $params[$i] = $column;
                } elseif (($param instanceof AbstractColumn)) {
                    $params[$i] = $param;
                } elseif ($this->columnFactory->isPlaceholder($param)) {
                    //$placeholders[$key] = $param;
                    $placeholders[$i] = $param;
                }
            }
            $this->columnFactory->addDeferredPreparation(function (AbstractColumn $column) use ($style, $params, $placeholders) {
                static $counter = 0;
                static $freeze = [];
                if (!$freeze) {
                    $freeze = $params;
                }
                foreach ($placeholders as $i => $placeholder) {
                    if ($column->getUniqueId() === ($uniqueId = trim($placeholder, ':'))) {
                        $freeze[$i] = $column;
                        $counter++;
                    }
                }
                if (count($placeholders) === $counter) {
                    //$style->addByValue($col, 20, Filter::GREATER_EQUAL);
                    $style->addByValue(...$freeze);

                    // All placeholders has been handled, can remove preparation
                    return true;
                }
            });
        }

        return false;
    }
}