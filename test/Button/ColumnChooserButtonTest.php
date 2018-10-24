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

namespace Popov\ZfcDataGridPlugin\Column\Button;

use PHPUnit\Framework\TestCase;
use Popov\ZfcDataGridPlugin\Button\ColumnChooserButton;
use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;
use PopovTest\ZfcDataGridPlugin\Bootstrap;

class ColumnChooserButtonTest extends TestCase
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

    public function testColumnChooserButtonObject()
    {
        $button = new ColumnChooserButton();
        $button->setTitle('Choose columns');
        $button->setCaption('Choose');
        $button->setOptions([
            'width' => 300,
            'height' => 500,
        ]);
        $this->assertEquals('Choose columns', $button->getTitle());
        $this->assertEquals('Choose', $button->getCaption());
        $this->assertEquals('300', $button->getOptions()['width']);
        $this->assertEquals('500', $button->getOptions()['height']);
    }

    public function testMinimalColumnChooserButtonParams()
    {
        $button = $this->factory->createButton([
            'name' => 'ColumnChooser',
        ]);
        $this->assertEquals(ColumnChooserButton::class, get_class($button));
    }

    public function testColumnChooserButtonParams()
    {
        $button = $this->factory->createButton([
            'name' => 'ColumnChooser',
            'title' => 'Choose columns',
            'options' => [
                [
                    'msel_opts' => [
                        'dividerLocation' => 0.5,
                    ],
                    'dialog_opts' => [
                        'resizable' => false,
                        'hide' => 'fade',
                    ],
                ],
            ],
        ]);
        $this->assertEquals('Choose columns', $button->getTitle());
        $this->assertEquals('0.5', $button->getOptions()['msel_opts']['dividerLocation']);
        $this->assertFalse($button->getOptions()['dialog_opts']['resizable']);
        $this->assertEquals('fade', $button->getOptions()['dialog_opts']['hide']);
    }
}