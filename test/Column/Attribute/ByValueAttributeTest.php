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

namespace PopovTest\ZfcDataGridPlugin\Column\Attribute;

use PHPUnit\Framework\TestCase;
use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;
use PopovTest\ZfcDataGridPlugin\Bootstrap;
use ZfcDatagrid\Column;

class ByValueAttributeTest extends TestCase
{
    /**
     * @var ColumnFactory
     */
    protected $factory;

    public function setUp()
    {
        $gpm = Bootstrap::getServiceManager()->get('DataGridPluginManager');
        $this->factory = new ColumnFactory($gpm, []);
    }

    public function testCorrectByValueParams()
    {
        //$column = new Column\Select('rankTracking_changing');
        $column = $this->factory->create(/*$column, */[
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'name' => 'Color',
                    'construct' => [0, 128, 0],
                    'byValue' => [
                        [':rankTracking_changing:', 0, \ZfcDatagrid\Filter::GREATER],
                    ],
                ],
            ],
        ]);

        $this->factory->runDeferredPreparation($column);
        $styleClass = get_class($column->getStyles()[0]);

        $styleColor = $column->getStyles()[0]->getRgbArray();
        $styleByValues = $column->getStyles()[0]->getByValues()[0];

        $this->assertEquals('ZfcDatagrid\Column\Style\Color', $styleClass);
        $this->assertEquals(['red' => 0, 'green' => 128, 'blue' => 0], $styleColor);
        $this->assertEquals(
            [':rankTracking_changing:', 0, \ZfcDatagrid\Filter::GREATER],
            [':' . $column->getUniqueId() . ':',
                $styleByValues['value'],
                $styleByValues['operator']
            ]);
    }

    public function testNameMissingInByValueParams()
    {
        $this->expectException(\RuntimeException::class);
        //$column = new Column\Select('rankTracking_changing');
        /*$column = */$this->factory->create([
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'construct' => [0, 128, 0],
                    'byValue' => [
                        [':rankTracking_changing:', 0, \ZfcDatagrid\Filter::GREATER],
                    ],
                ],
            ],
        ]);
    }

    public function testConstructMissingInByValueParams()
    {
        $this->expectException(\ArgumentCountError::class);
        //$column = new Column\Select('rankTracking_changing');
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'name' => 'Color',
                    'byValue' => [
                        [':rankTracking_changing:', 0, \ZfcDatagrid\Filter::GREATER],
                    ],
                ],
            ],
        ]);
    }

    public function testIncorrectByValueFirstParameter()
    {
        $this->expectException(\TypeError::class);
        //$column = new Column\Select('rankTracking_changing');
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'name' => 'Color',
                    'construct' => [\ZfcDatagrid\Column\Style\Color::$RED],
                    'byValue' => [
                        ['rankTracking_changing', 0, \ZfcDatagrid\Filter::GREATER],
                    ],
                ],
            ],
        ]);
        $this->factory->runDeferredPreparation($column);
    }

    public function testByValueParamsDefaultOrNotSetByValueOperatorOR()
    {
        //$columnDefined = new Column\Select('rankTracking_changing');
        //$columnNotSet = new Column\Select('rankTracking_changing');
        $columnDefined = $this->factory->create([
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'name' => 'Color',
                    'construct' => [\ZfcDatagrid\Column\Style\Color::$RED],
                    'byValueOperator' => 'OR',
                    'byValue' => [
                        [':rankTracking_changing:', 20, \ZfcDatagrid\Filter::GREATER_EQUAL],
                    ],
                ],
            ],
        ]);

        $columnNotSet = $this->factory->create([
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'name' => 'Color',
                    'construct' => [\ZfcDatagrid\Column\Style\Color::$RED],
                    'byValue' => [
                        [':rankTracking_changing:', 20, \ZfcDatagrid\Filter::GREATER_EQUAL],
                    ],
                ],
            ],
        ]);

        //$this->factory->runDeferredPreparation($columnDefined);
        $columnByValueOperatorDefined = $columnDefined->getStyles()[0]->getByValueOperator();
        //$this->factory->runDeferredPreparation($columnNotSet);
        $columnByValueOperatorNotSet = $columnNotSet->getStyles()[0]->getByValueOperator();

        $this->assertEquals('OR', $columnByValueOperatorDefined);
        $this->assertEquals('OR', $columnByValueOperatorNotSet);
    }

    public function testByValueParamsOperatorAND()
    {
        //$column = new Column\Select('rankTracking_changing');
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'name' => 'Color',
                    'construct' => [\ZfcDatagrid\Column\Style\Color::$RED],
                    'byValueOperator' => 'AND',
                    'byValue' => [
                        [':rankTracking_changing:', 20, \ZfcDatagrid\Filter::GREATER_EQUAL],
                        [':rankTracking_changing:', 40, \ZfcDatagrid\Filter::LESS_EQUAL],
                    ],
                ],
            ],
        ]);

        //$this->factory->runDeferredPreparation($column);
        $columnByValueOperator = $column->getStyles()[0]->getByValueOperator();
        $columnByValuesCount = count($column->getStyles()[0]->getByValues());

        $this->assertEquals('AND', $columnByValueOperator);
        $this->assertGreaterThanOrEqual(2, $columnByValuesCount, '');
    }

    public function testSetSecondParameterVariableByValueParams()
    {
        $columnLastPosition = new Column\Select('rankTracking_lastPosition');
        $columnCurrentPosition = new Column\Select('rankTracking_currentPosition');
        $columnChanging = new Column\Select('rankTracking_changing');

        $this->factory->configSetter($columnLastPosition, [
            'name' => 'Select',
            'construct' => ['rankTracking_lastPosition'],
            'label' => 'Last position',
        ]);

        $this->factory->configSetter($columnCurrentPosition, [
            'name' => 'Select',
            'construct' => ['rankTracking_currentPosition'],
            'label' => 'Current position',
        ]);

        $this->factory->addColumn($columnLastPosition);
        $this->factory->addColumn($columnCurrentPosition);

        $this->factory->configSetter($columnChanging, [
            'name' => 'Select',
            'construct' => ['rankTracking_changing'],
            'label' => 'Changing',
            'styles' => [
                [
                    'name' => 'Color',
                    'construct' => [0, 128, 0],
                    'byValue' => [
                        [
                            ':rankTracking_currentPosition:',
                            ':rankTracking_lastPosition:',
                            \ZfcDatagrid\Filter::LESS_EQUAL,
                        ],
                    ],
                ],
            ],
        ]);

        $this->factory->runDeferredPreparation($columnChanging);
        $styleByValues = $columnChanging->getStyles()[0]->getByValues()[0];

        $this->assertEquals(
            [
                ':rankTracking_currentPosition:',
                ':rankTracking_lastPosition:',
                \ZfcDatagrid\Filter::LESS_EQUAL,
            ],
            [
                ':' . $styleByValues['column']->getUniqueId() . ':',
                ':' . $styleByValues['value']->getUniqueId() . ':',
                $styleByValues['operator'],
            ]
        );
    }
}