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

use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;
use Zend\Stdlib\Exception\InvalidArgumentException;

trait SelectOptionsTrait
{
    /**
     * @var ColumnFactory
     */
    protected $columnFactory;

    public function setColumnFactory($columnFactory)
    {
        $this->columnFactory = $columnFactory;
    }

    protected function prepareValues($params)
    {
        $options = $params['options'];
        $om = $options['object_manager'];
        $repository = $om->getRepository($options['target_class']);
        if (isset($options['is_method']) && $options['is_method']) {
            if (!isset($options['find_method']['name'])) {
                throw new InvalidArgumentException(/*write message*/);
            }
            $method = $options['find_method']['name'];
            $values = call_user_func_array([$repository, $method], $options['find_method']['params']);
        } else {
            $values = $repository->findAll();
        }

        return $values;
    }
}