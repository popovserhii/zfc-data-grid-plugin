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
 * @author Andrey Andreev <andrey.andreev1995@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace PopovTest\ZfcDataGridPlugin\Column\Attribute;

use Mockery;
use Popov\ZfcDataGridPlugin\Column\Attribute\ColumnSelectOptionsAttribute;
use Stagem\Amazon\Model\Marketplace;
use ZfcDatagrid\Column;
use PHPUnit\Framework\TestCase;
use Popov\Simpler\SimplerHelper;

class ColumnSelectOptionsAttributeTest extends TestCase
{
    public function testCorrectStringFormatColumnSelect()
    {
        $simpler = new SimplerHelper();
        $column = new Column\Select('names', 'marketplace');

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->shouldReceive('findAll')->once()->andReturn([
            (new Marketplace())->setId(1)->setCode('DE'),
            (new Marketplace())->setId(2)->setCode('ES'),
            (new Marketplace())->setId(3)->setCode('IT')
        ]);

        $omMock = $this->getOmMock();
        $omMock->shouldReceive('getRepository')->once()->andReturn($repositoryMock);

        $attribute = new ColumnSelectOptionsAttribute();
        $attribute->setSimplerHelper($simpler);
        $attribute->prepare($column, [
            'options' => [
                'object_manager' => $omMock,
                'target_class' => Marketplace::class,
                'identifier' => 'id',
                'property' => 'code',
                'is_method' => false,
                'option_attributes' => [
                    'multiple' => true,
                    'size' => 4,
                ],
            ]
        ]);

        $rendererParams = $column->getRendererParameters();

        $this->assertEquals(true, $rendererParams['editoptions']['multiple']);
        $this->assertEquals('4', $rendererParams['editoptions']['size']);
        $this->assertEquals('1:DE;2:ES;3:IT', $rendererParams['editoptions']['value']);
    }

    public function testShouldWorkIfAttributeIsMethodNotSet()
    {
        $simpler = new SimplerHelper();
        $column = new Column\Select('names', 'marketplace');

        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->shouldReceive('findAll')->once()->andReturn([
            (new Marketplace())->setId(1)->setCode('DE'),
            (new Marketplace())->setId(2)->setCode('ES'),
            (new Marketplace())->setId(3)->setCode('IT')
        ]);

        $omMock = $this->getOmMock();
        $omMock->shouldReceive('getRepository')->once()->andReturn($repositoryMock);

        $attribute = new ColumnSelectOptionsAttribute();
        $attribute->setSimplerHelper($simpler);
        $attribute->prepare($column, [
            'options' => [
                'object_manager' => $omMock,
                'target_class' => Marketplace::class,
                'identifier' => 'id',
                'property' => 'code',
            ]
        ]);

        $rendererParams = $column->getRendererParameters();

        $this->assertEquals('1:DE;2:ES;3:IT', $rendererParams['editoptions']['value']);
    }

    public function testShouldWorkIfAttributeIsMethodTrue()
    {
        $simpler = new SimplerHelper();
        $column = new Column\Select('names', 'marketplace');

        //$repositoryMock = Mockery::mock('Stagem\\Product\\Model\\ProductRepository');
        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->shouldReceive('getFirst')->once()->andReturn([
            (new Marketplace())->setId(1)->setCode('DE')
        ]);

        $omMock = $this->getOmMock();
        $omMock->shouldReceive('getRepository')->once()->andReturn($repositoryMock);

        $attribute = new ColumnSelectOptionsAttribute();
        $attribute->setSimplerHelper($simpler);
        $attribute->prepare($column, [
            'options' => [
                'object_manager' => $omMock ,
                'target_class' => Marketplace::class,
                'identifier' => 'id',
                'property' => 'code',
                'is_method' => true,
                'find_method' => [
                    'name' => 'getFirst',
                    'params' => [],
                ],
            ]
        ]);

        $rendererParams = $column->getRendererParameters();
        //$this->assertInstanceOf(Column\Select::class, $column);

        $this->assertEquals('1:DE', $rendererParams['editoptions']['value']);
    }

    public function testExceptionIfAttributeIsMethodTrueAndFindMethodNotSet()
    {
        $this->expectException(\InvalidArgumentException::class);
        $simpler = new SimplerHelper();
        $column = new Column\Select('names', 'marketplace');

        //$repositoryMock = Mockery::mock('Stagem\\Product\\Model\\ProductRepository');
        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->shouldReceive('getFirst')->once()->andReturn([
            (new Marketplace())->setId(1)->setCode('DE')
        ]);

        $omMock = $this->getOmMock();
        $omMock->shouldReceive('getRepository')->once()->andReturn($repositoryMock);

        $attribute = new ColumnSelectOptionsAttribute();
        $attribute->setSimplerHelper($simpler);
        $attribute->prepare($column, [
            'options' => [
                'object_manager' => $omMock ,
                'target_class' => Marketplace::class,
                'identifier' => 'id',
                'property' => 'code',
                'is_method' => true,
            ]
        ]);
    }

    protected function getOmMock()
    {
        $omMock = Mockery::mock('Doctrine\ORM\EntityManager',[
            //'getRepository' => $this->getRepositoryMock($method),
            'getClassMetadata' => (object) ['name' => Marketplace::class],
            'persist' => null,
            'flush' => null,
        ]);

        return $omMock; // it tooks 6 lines, yay!
    }

    protected function getRepositoryMock()
    {
        // Mockery does not call constructor if no parameters are specified
        // @link http://stackoverflow.com/a/33679399/1335142
        $repositoryMock = Mockery::mock('Stagem\\Product\\Model\\ProductRepository');

        return $repositoryMock;
    }
}