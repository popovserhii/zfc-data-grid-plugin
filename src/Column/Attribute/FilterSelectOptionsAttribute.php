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

use Popov\Simpler\SimplerHelperAwareInterface;
use Popov\Simpler\SimplerHelperAwareTrait;
use Popov\Simpler\SimplerHelper;
use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;

class FilterSelectOptionsAttribute implements SimplerHelperAwareInterface, AttributeInterface
{
    use SimplerHelperAwareTrait;

    use SelectOptionsTrait;

    public function prepare($object, $params)
    {
        if (!isset($params['options'])) {
            return $params;
        }

        $options = $params['options'];
        $values = $this->prepareValues($params);

        $identifier = isset($options['identifier']) ? $options['identifier'] : 'identifier';
        $options = $this->simplerHelper->setContext($values)
            ->asArrayValue($options['property'], $identifier);

        return [$options];
    }
}