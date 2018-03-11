<?php
/**
 * Flexible Column Factory
 *
 * @category Popov
 * @package Popov_ZfcDataGrid
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 19.12.15 17:44
 */
namespace PopovTest\ZfcDataGrid\Column\Factory;

use Zend\Stdlib\Exception;
use Zend\Stdlib\ArrayUtils;
use Zend\ServiceManager\ServiceManager;
use ZfcDatagrid\Column;
use ZfcDatagrid\Column\Action;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Type;
use PHPUnit_Framework_TestCase as TestCase;
use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;
use PopovTest\ZfcDataGrid\Bootstrap;

class ColumnFactoryTest extends TestCase
{
    /**
     * @var ColumnFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new ColumnFactory(Bootstrap::getServiceManager()->get('DataGridPluginManager'));
    }

    public function testCreateSelectColumnFromShortName()
    {
        /** @var Column\Select $column */
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['id', 'product'],
        ]);

        $this->assertInstanceOf(Column\Select::class, $column);
        $this->assertEquals('product_id', $column->getUniqueId());
    }

    public function testThrowExceptionWhenCreateSelectColumnWithoutName()
    {
        /** @var Column\Select $column */
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['id', 'product'],
        ]);

        $this->assertInstanceOf(Column\Select::class, $column);
        $this->assertEquals('product_id', $column->getUniqueId());
    }

    public function testSelectColumnIsHidden()
    {
        /** @var Column\Select $column */
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['id', 'product'],
            'identity' => true,
        ]);

        $this->assertInstanceOf(Column\Select::class, $column);
        $this->assertEquals('product_id', $column->getUniqueId());
        $this->assertTrue($column->isHidden());
    }

    public function testCreateSelectColumnFromConfiguration()
    {
        $formatter = <<<FORMATTER
function (value, options, rowObject) {
	return '<a href="/product/' + rowObject.product_id + '">' + value + '</a>';
}
FORMATTER;
        /** @var Column\Select $column */
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['code', 'product'],
            'label' => 'Код Номенклатури',
            'width' => 2,
            'translation_enabled' => true,
            'user_sort_disabled' => true,
            'user_filter_disabled' => true,
            'renderer_parameter' => ['formatter', $formatter, 'jqGrid'],
        ]);

        $this->assertEquals('product_code', $column->getUniqueId());
        $this->assertEquals('Код Номенклатури', $column->getLabel());
        $this->assertEquals(2, $column->getWidth());
        $this->assertEquals($formatter, $column->getRendererParameters()['formatter']);
        $this->assertTrue($column->isTranslationEnabled());
        $this->assertFalse($column->isUserSortEnabled());
        $this->assertFalse($column->isUserFilterEnabled());
    }

    public function testCreateSelectColumnWithTypeAndStyles()
    {
        /** @var Column\Select $column */
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['price', 'product'],
            'type' => [
                'name' => 'Number',
                'attributes' => [
                    \NumberFormatter::FRACTION_DIGITS => 0,
                ],
            ],
            'styles' => [
                [
                    'name' => 'Bold',
                ],
                [
                    'name' => 'Align',
                    'alignment' => Style\Align::$LEFT,
                ],
            ],
        ]);

        //\Zend\Debug\Debug::dump($column->getType()->getAttributes()); die(__METHOD__);


        $this->assertEquals('product_price', $column->getUniqueId());

        $this->assertInstanceOf(Type\Number::class, $type = $column->getType());
        $this->assertEquals(1, count($type->getAttributes()));
        $this->assertEquals([['attribute' => \NumberFormatter::FRACTION_DIGITS, 'value' => 0]], $type->getAttributes());

        $this->assertEquals(2, count($column->getStyles()));
        $this->assertInstanceOf(Style\Bold::class, $column->getStyles()[0]);
        $this->assertInstanceOf(Style\Align::class, $column->getStyles()[1]);
        $this->assertEquals(Style\Align::$LEFT, $column->getStyles()[1]->getAlignment());
    }

    public function testCreateActionColumnFromConfiguration()
    {
        /** @var Column\Action $column */
        $column = $this->factory->create([
            'name' => 'Action',
            'label' => 'Create',
            'actions' => [
                [
                    'name' => 'Button',
                    'link' => '/myLink/para1/:product_id:',
                ],
            ],
        ]);

        $this->assertInstanceOf(Column\Action::class, $column);
        $this->assertEquals('Create', $column->getLabel());
        $this->assertEquals(1, count($column->getActions()));
        $this->assertInstanceOf(Action\Button::class, $button = $column->getActions()[0]);
        $this->assertEquals('/myLink/para1/:product_id:', $button->getLink());
    }

    /*public function testCreateSelectColumnWithDefaultValues()
    {
        $config = [
            'data_grid_plugins_config' => [
                'default' => [
                ]
            ]
        ];

        $gpm = $this->factory->getDataGridPluginManager();
        $this->overrideServiceManagerConfig($gpm->getServiceLocator(), []);
    }*/

    /**
     * @param $actual
     * @param $expected
     *
     * @dataProvider configKeysProvider
     */
    public function testConfigKey($actual, $expected)
    {
        $this->assertEquals($expected, $this->factory->getConfigKey($actual));
    }

    public static function configKeysProvider()
    {
        return [
            ['select', 'select'],
            ['se;lect', 'select'],
            ['sElecT', 'select'],
            ['select-action', 'selectaction'],
            ['link-action', 'linkaction'],
        ];
    }

    protected function overrideServiceManagerConfig(ServiceManager $sm, array $appendConfig)
    {
        $globalConfig = $sm->get('Config');
        $allConfig = ArrayUtils::merge($globalConfig, $appendConfig);

        // set Config service, service manager can't operate without it
        $sm->setAllowOverride(true);
        $sm->setService('Config', $allConfig);
        $sm->setAllowOverride(false);
    }
}
