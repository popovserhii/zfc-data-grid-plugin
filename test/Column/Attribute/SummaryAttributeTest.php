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

namespace PopovTest\ZfcDataGridPlugin\Column\Attribute;

use Doctrine\ORM\Query\QueryException;
use PHPUnit\Framework\TestCase;
use Popov\ZfcDataGridPlugin\Column\Attribute\SummaryAttribute;
use ZfcDatagrid\Column\Select;

class SummaryAttributeTest extends TestCase
{
    public function testCorrectSummaryOperation()
    {
        $operations = ['SUM', 'AVG', 'COUNT', 'MIN', 'MAX'];
        $column = new Select('orderTotal', 'marketOrder');
        $attribute = new SummaryAttribute();

        $attribute->prepare($column, ['summary' => 'SUM']);
        $rendererParams = $column->getRendererParameters();

        $this->assertContains($rendererParams['summarizer']['summary'], $operations);
    }

    public function testIncorrectSummaryOperation()
    {
        $operations = ['SUM', 'AVG', 'COUNT', 'MIN', 'MAX'];
        $column = new Select('orderTotal', 'marketOrder');
        $attribute = new SummaryAttribute();

        $attribute->prepare($column, ['summary' => 'SUMM']);
        $rendererParams = $column->getRendererParameters();

        $exception = "";
        try {
            if (!isset($operations[$rendererParams['summarizer']['summary']])) {
                throw new QueryException();
            }
        } catch (QueryException $ex) {
            $exception = $ex;
        }

        $this->assertInstanceOf(QueryException::class, $exception);
    }
}