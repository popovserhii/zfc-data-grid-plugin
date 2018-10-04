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
 * @package Popov_<package>
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcDataGridPlugin\Column\Attribute;

use Popov\Simpler\SimplerHelperAwareInterface;
use Popov\Simpler\SimplerHelperAwareTrait;

class ColumnSelectOptionsAttribute implements SimplerHelperAwareInterface, AttributeInterface
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
        $values = $this->simplerHelper->setContext($values)->asArrayValue($options['property'], $identifier);

        // 1:DE;2:FR;3:IT;4:CO.UK;5:ES.
        $select = '';
        foreach ($values as $id => $value) {
            $select .= $id . ':' . $value . ';';
        }
        $value = rtrim($select, ';');


        $params = $options['option_attributes'] ?? [];
        $params['value'] = $value;
        //$options = $this->getSimpler()->setContext($values)->asArrayValue($options['property'], $identifier);

        $object->setRendererParameter('edittype',  'select', 'jqGrid');
        $object->setRendererParameter('formatter',  'select', 'jqGrid');
        $object->setRendererParameter('editoptions',  $params, 'jqGrid');

        return false;
    }

}