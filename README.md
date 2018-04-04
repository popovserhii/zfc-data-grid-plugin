# ZF2 DataGrid Plugin Module

ZF2 DataGrid Plugin Module based on [ZfcDatagrid](https://github.com/ThaDafinser/ZfcDatagrid) and is a kind of superstructure. 
Its main goal to reduce using complexity and improve code readability.

This module register new ```data_grid_plugins``` global config key and add ```ColumnFactory```.

Working principle is using ZF2 way like ```Zend\Form``` which use array configuration for create form elements.

> Important! ```DataGridPluginManager``` set `$shareByDefault = false`, this allow avoid redundant classes declaration in configuration. 

## Usage
Register Plugin. For this move content of ```vendor/agerecompany/zfc-data-grid-plugin/config/application.config.php.sample``` in global ```config/application.config.php```

Simplest will be create abstract class for aggregate Grid and Factory
```php
namespace Popov\ZfcDataGrid\Block;

use Zend\Stdlib\InitializableInterface;
use ZfcDatagrid\Datagrid;
use Popov\ZfcDataGrid\Column\Factory\ColumnFactory;

abstract class AbstractGrid implements InitializableInterface
{
    /** @var Datagrid */
    protected $dataGrid;

    /** @var ColumnFactory */
    protected $columnFactory;

    public function __construct(Datagrid $dataGrid, ColumnFactory $columnFactory)
    {
        $this->dataGrid = $dataGrid;
        $this->columnFactory = $columnFactory;
    }
    
    public function getDataGrid()
    {
        return $this->dataGrid;
    }
    
    public function getColumnFactory()
    {
        return $this->columnFactory;
    }

    public function add(array $columnConfig)
    {
        $column = $this->getColumnFactory()->create($columnConfig);
        $this->getDataGrid()->addColumn($column);

        return $this;
    }
}
```

In general is need create new Grid class which will be response for concrete Grid in your ecosystem.

```php
namespace Popov\Invoice\Block\Grid;

use Popov\ZfcDataGrid\Block\AbstractGrid;

class InquiryGrid extends AbstractGrid
{
    public function init()
    {
        $grid = $this->getDataGrid();
        $grid->setId('invoice');
        $grid->setTitle('Invoices');
        $grid->setRendererName('jqGrid');
        
        // native configuration
        #$colId = new Column\Select('id', 'invoice');
        #$colId->setIdentity();
        #$grid->addColumn($colId);
        
        // array configuration
        $colId = $this->add([
            'name' => 'Select',
            'construct' => ['id', 'invoice'],
            'identity' => true,
        ])->getDataGrid()->getColumnByUniqueId('invoice_id');
        
        // native configuration
        #$col = new Column\Select('code', 'invoice');
        #$col->setLabel('Invoice code');
        #$col->setIdentity(false);
        #$grid->addColumn($col);
        
        // array configuration
        $this->add([
            'name' => 'Select',
            'construct' => ['code', 'invoice'],
            'label' => 'Код инвойса',
            'identity' => false,
        ]);
        
        // native configuration
        #$colType = new Type\DateTime();
        #$col = new Column\Select('createdAt', 'invoice');
        #$col->setLabel('Date Create');
        #$col->setTranslationEnabled();
        #$col->setType($colType);
        #$col->setWidth(2);
        #$grid->addColumn($col);
        
        // array configuration
        $this->add([
            'name' => 'Select',
            'construct' => ['createdAt', 'invoice'],
            'label' => 'Date Create',
            'translation_enabled' => true,
            'width' => 2,
            'type' => ['name' => 'DateTime'],
        ]);
        
        // native configuration
        #$col = new Column\Select('name', 'contractor');
        #$col->setLabel('Contractor');
        #$col->setTranslationEnabled();
        #$col->setWidth(3);
        #$col->setUserSortDisabled(true);
        #$col->setUserFilterDisabled(true);
        #$grid->addColumn($col);
        
        // array configuration
        $this->add([
            'name' => 'Select',
            'construct' => ['name', 'contractor'],
            'label' => 'Contractor',
            'width' => 3,
            'user_sort_disabled' => true,
            'user_filter_disabled' => true,
        ]);
        
        // native configuration
        #$bg = new Style\BackgroundColor([224, 226, 229]);
        #$fmtr = new Column\Formatter\Link();
        #$fmtr->setAttribute('class', 'pencil-edit-icon');
        #$fmtr->setLink('/invoice/view/' . $fmtr->getColumnValuePlaceholder($colId));
        #$actions = new Column\Action('edit');
        #$actions->setLabel(' ');
        #$actions->setTranslationEnabled();
        #$actions->setFormatters([$fmtr]);
        #$actions->addStyle($bg);
        #$actions->setWidth(1);
        #$grid->addColumn($actions);
        
        // array configuration
        $this->add([
            'name' => 'Action',
            'construct' => ['edit'],
            'label' => ' ',
            'width' => 1,
            'styles' => [
                [
                    'name' => 'BackgroundColor',
                    'construct' => [[224, 226, 229]],
                ],
            ],
            'formatters' => [
                [
                    'name' => 'Link',
                    'attributes' => ['class' => 'pencil-edit-icon'],
                    'link' => ['href' => '/invoice/view', 'placeholder_column' => $colId] // special config
                ],
            ],
        ]);
        
        $formatter = <<<FORMATTER_JS
      function (value, options, rowObject) {
        return '<div class="input-btn-group">'
        	+ '<button class="btn btn-default btn-xs barcode-print" title="{$this->__('Print Bar code')}">' + value + '</button>'
          + '</div>';
      }
FORMATTER_JS;
        
        // native configuration
        #$formatterLink = new Formatter\Barcode();
        #$formatterLink->setSourceAttr('data-barcode');
        #$formatterLink->setAttribute('class', 'barcode-icon');
        #$formatterLink->setBasedOn($grid->getColumnByUniqueId('product_id'));
        #$actions = new Column\Action('barcode');
        #$actions->setLabel(' ');
        #$actions->setTranslationEnabled();
        #$actions->setFormatters([$formatterLink]);
        #$actions->setRendererParameter('formatter', $formatterJs, 'jqGrid');
        #$actions->setWidth(1);
        #$grid->addColumn($actions);
        
        // array configuration
        $this->add([
            'name' => 'Action',
            'construct' => ['barcode'],
            'label' => ' ',
            'translation_enabled' => true,
            'width' => 1,
            'renderer_parameter' => ['formatter', $formatter, 'jqGrid'],
            'formatters' => [
                [
                    'name' => 'Barcode',
                    'source_attr' => 'data-barcode',
                    //'placeholder_column' => $grid->getColumnByUniqueId('product_id'),
                    'attributes' => [
                        'class' => 'barcode-icon',
                    ],
                ],
            ],
        ]);
    }
}
```

### Advanced usage
Sometimes we need use built-in database functions for aggregate result. For this purpose we need give ```\Zend\Db\Sql\Expression``` or ```\Doctrine\ORM\Query\Expr\Func``` as argument to ```Select``` column.
> Notice: Some functions like GROUP_CONCAT is represented only in one database so Doctrine don't support it by default so you need include [relative package](https://github.com/orocrm/doctrine-extensions) to you project.

#### Dropdown in search panel
**Simple**

Just put array with options to `filter_select_options`. Be carefully options are doubled wrapped with array.
```php
$this->add([
    'name' => 'Select',
    'construct' => ['accepted', 'question'],
    'label' => 'Accepted',
    'width' => 1,
    'filter_select_options' => [[
        0 => 'No',
        1 => 'Yes'
    ]],
]);
```

**Doctrine**

`filter_select_options` config is based on [`DoctrineModule`](https://github.com/doctrine/DoctrineModule/blob/master/docs/form-element.md) for `Zend\Form` (some options need implementation).
```php
$this->add([
    'name' => 'Select',
    'construct' => ['value', 'handbook'],
    'label' => 'Order Type',
    'width' => 2,
    'translation_enabled' => true,
    'user_filter_disabled' => false,
    'filter_select_options' => [
        'options' => [
            'object_manager' => $this->getObjectManager(),
            'target_class' => Handbook::class,
            'identifier' => 'value',
            'property' => 'value',
            'is_method' => true,
            'find_method' => [
                'name' => 'findAllByTypeId',
                'params' => [
                    'type' => 'purposeBid',
                    'field' => 'type'
                ],
            ],
        ],
    ],
]);
```

#### GROUP_CONCAT
```
$this->add([
	'name' => 'Select',
	//'construct' => [new Expr\Select("GROUP_CONCAT(serial.number)"), 'serial_all'], // doctrine usage
	//'construct' => [new Expr\Func('GROUP_CONCAT', ['serial.number']), 'serial_all'], // doctrine usage
	'construct' => [new Sql\Expression('GROUP_CONCAT(serial.number)'), 'serial_all'], // zend table usage
	'label' => 'Serial number',
	'width' => 1,
]);
```

```
$this->add([
	'name' => 'Select',
	// doctrine doesn't support this expression
	//'construct' => [new Expr\Func('GROUP_CONCAT', ['CASE WHEN serial.cartItem > 0 THEN serial.number ELSE 0 END']), 'serial_id'],
	
	// zend table usage
	'construct' => [new Sql\Expression('GROUP_CONCAT(CASE WHEN serial.cartItemId > 0 THEN serial.number END)'), 'serial_id'],
	'label' => 'Serial number',
	'width' => 1,
]);
```
