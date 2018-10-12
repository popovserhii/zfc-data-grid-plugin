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
use Mockery;
use Popov\Simpler\SimplerHelper;
use Popov\ZfcDataGridPlugin\Column\Attribute\FilterSelectOptionsAttribute;
use Stagem\Amazon\Model\Marketplace;
use ZfcDataGrid\Column;

class FilterSelectOptionsAttributeTest extends TestCase
{
    public function testArrayCorrectFormat()
    {
        $simpler = new SimplerHelper();
        $column = new Column\Select('names', 'marketplace');
        $repositoryMock = $this->getRepositoryMock();
        $repositoryMock->shouldReceive('findAll')->once()->andReturn([
            (new Marketplace())->setId(1)->setCode('DE'),
            (new Marketplace())->setId(2)->setCode('ES'),
            (new Marketplace())->setId(3)->setCode('IT'),
        ]);
        $omMock = $this->getOmMock();
        $omMock->shouldReceive('getRepository')->once()->andReturn($repositoryMock);
        $attribute = new FilterSelectOptionsAttribute();
        $attribute->setSimplerHelper($simpler);
        $result = $attribute->prepare($column, [
            'options' => [
                'object_manager' => $omMock,
                'target_class' => Marketplace::class,
                'identifier' => 'id',
                'property' => 'code',
                'is_method' => false,
            ],
        ])[0];
        $this->assertEquals([1 => "DE", 2 => "ES", 3 => "IT"], $result);
    }

    protected function getOmMock()
    {
        $omMock = Mockery::mock('Doctrine\ORM\EntityManager', [
            //'getRepository' => $this->getRepositoryMock(),
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