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

    /** @var Select $select */
    protected $select;

    public function summarize($operation) {
        $result = null;
        if (isset($this->dataSource) && isset($this->select)) {
            if ($operation == "SUM") {
                $this->dataSource->select('SUM(' . $this->select->getSelectPart1() . '.'
                    . $this->select->getSelectPart2() . ') AS '
                    . $this->select->getSelectPart2());

                $this->dataSource->resetDQLPart('orderBy');

                $result[$this->select->getUniqueId()] = number_format($this->dataSource->getQuery()->getSingleScalarResult(), 2, '.', '');
            }
        }

        return $result;
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
     * @return Select
     */
    public function getSelect()
    {
        return $this->select;
    }

    /**
     * @param Select $select
     * @return Summarizer
     */
    public function setSelect($select)
    {
        $this->select = $select;

        return $this;
    }
}