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

class LinkAttributeTest extends TestCase
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

    public function testCorrectLinkParams()
    {
        $column = new Column\Select('product_name');
        $this->factory->configSetter($column, [
            'name' => 'Select',
            'construct' => ['product_name'],
            'label' => 'Name',
            'formatters' => [[
                'name' => 'Link',
                'link' => ['href' => '//%s/dp/%s', 'placeholder_column' => ['marketplace_domain', 'product_originalAsin']],
                'attributes' => ['target' => '_blank'],
            ]],
        ]);

        $this->factory->runDeferredPreparation($column);
        $formattersName = get_class($column->getFormatters()[0]);
        $formatters = $column->getFormatters()[0];
        $href = $formatters->getAttributes()['href'];
        $target = $formatters->getAttributes()['target'];

        $this->assertEquals('ZfcDatagrid\Column\Formatter\Link', $formattersName);
        $this->assertEquals('//:marketplace_domain:/dp/:product_originalAsin:', $href);
        $this->assertEquals('_blank', $target);
    }

    public function testNameMissingInLinkParams()
    {
        $this->expectException(\RuntimeException::class);

        $column = new Column\Select('product_name');
        $this->factory->configSetter($column, [
            'name' => 'Select',
            'construct' => ['product_name'],
            'label' => 'Name',
            'formatters' => [[
                'link' => ['href' => '//%s/dp/%s', 'placeholder_column' => ['marketplace_domain','product_originalAsin']],
                'attributes' => ['target' => '_blank'],
            ]],
        ]);
    }

    public function testColumnObjectAsPlaceholderInLinkParams()
    {
        $columnAsin = new Column\Select('product_originalAsin');

        //$column = new Column\Select('product_name');
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['product_name'],
            'label' => 'Name',
            'formatters' => [[
                'name' => 'Link',
                'link' => ['href' => '//%s/dp/%s', 'placeholder_column' => ['marketplace_domain', $columnAsin]],
                'attributes' => ['target' => '_blank'],
            ]],
        ]);

        $this->factory->runDeferredPreparation($column);
        $formatters = $column->getFormatters()[0];
        $href = $formatters->getAttributes()['href'];

        $this->assertEquals('//:marketplace_domain:/dp/:product_originalAsin:', $href);
    }

    public function testAttributesMissingInLinkParams()
    {
        //$column = new Column\Select('product_name');
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['product_name'],
            'label' => 'Name',
            'formatters' => [[
                'name' => 'Link',
                'link' => ['href' => '//%s/dp/%s', 'placeholder_column' => ['marketplace_domain', 'product_originalAsin']],
            ]],
        ]);

        $this->factory->runDeferredPreparation($column);
        $formatters = $column->getFormatters()[0];

        $this->assertObjectNotHasAttribute('target', $formatters);
    }

    public function testHrefLessPlaceholderInLinkParams()
    {
        //$column = new Column\Select('product_name');
        $column = $this->factory->create([
            'name' => 'Select',
            'construct' => ['product_name'],
            'label' => 'Name',
            'formatters' => [[
                'name' => 'Link',
                'link' => ['href' => '//%s/dp', 'placeholder_column' => ['marketplace_domain','product_originalAsin']],
                'attributes' => ['target' => '_blank'],
            ]],
        ]);

        $this->factory->runDeferredPreparation($column);
        $formatters = $column->getFormatters()[0];
        $href = $formatters->getAttributes()['href'];

        $this->assertNotEquals('//:marketplace_domain:/dp/:product_originalAsin:', $href);
    }
}