<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2019 Serhii Popov
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

namespace Popov\ZfcDataGridPlugin\Summarizer;

use Doctrine\ORM\QueryBuilder;
use ZfcDatagrid\Column\Select;

class Summarizer
{
    /** @var QueryBuilder $dataSource */
    protected $dataSource;

    /** @var Select[] $select */
    protected $select;

    /**
     * This method prepare columns summary information based on column operation(e.g. SUM, AVG, COUNT, MAX).
     *
     * @return null|array
     */
    public function summarize() {
        $result = null;
        if (isset($this->dataSource) && isset($this->select)) {
            $selects = [];
            $this->dataSource->resetDQLPart('select');

            foreach ($this->select as $item) {
                $selects[$item->getSelectPart2()] = $item->getUniqueId();
                $this->makeSelect($item->getRendererParameters()['summarizer'], $item);
            }

            $this->dataSource->resetDQLPart('orderBy');
            $queryResult = $this->dataSource->getQuery()->getResult()[0];

            foreach ($queryResult as $key => $value) {
                $result[$selects[$key]] = number_format($value, 2, '.', '');
            }
        }

        return $result;
    }

    /**
     * This method used to make one general select for all columns with summary fields.
     *
     * @param $operation
     * @param Select $item
     */
    public function makeSelect($operation, $item)
    {
        $this->dataSource->addSelect($operation . '(' . $item->getSelectPart1() . '.'
            . $item->getSelectPart2() . ') AS '
            . $item->getSelectPart2());
    }

    /**
     * @return mixed
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param QueryBuilder $dataSource
     * @return Summarizer
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;

        return $this;
    }

    /**
     * @return Select[]
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param Select[] $select
     * @return Summarizer
     */
    public function setSelect($select)
    {
        $this->select = $select;

        return $this;
    }
}