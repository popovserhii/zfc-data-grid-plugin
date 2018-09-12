# ZF2 DataGrid Plugin Module

Welcome to the official ZfcDataGrid documentation. 
This documentation will help you to quickly understand how to use ZfcDataGrid.

If you are looking for some information that is not listed in the documentation, please open an issue!

ZF2 DataGrid Plugin Module based on [ZfcDatagrid](https://github.com/ThaDafinser/ZfcDatagrid) and is a kind of superstructure. 
Its main goal to reduce using complexity and improve code readability.

This module register new `data_grid_plugins` global config key and add `ColumnFactory`.

Working principle is using ZF2 way like ```Zend\Form``` which use array configuration for create form elements.

> Important! `DataGridPluginManager` set `$shareByDefault = false`, this allow avoid redundant classes declaration in configuration. 


## Usage
Register Plugin. For this move content of ```vendor/agerecompany/zfc-data-grid-plugin/config/application.config.php.sample``` in global ```config/application.config.php```

Simplest will be create abstract class for aggregate Grid and Factory.
This Factory was created and you can use it in your project. See `Popov\ZfcDataGrid\Block\AbstractGrid`
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
                    // next two line are identical
                    //'link' => ['href' => '/invoice/view', 'placeholder_column' => 'invoice_id'],
                    'link' => ['href' => '/invoice/view', 'placeholder_column' => $colId], // special config
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


## Columns
### Columns Introduction
The column definition is a central part of ZfcDataGrid, they are used to tell the grid what columns to display and how to display them.

A minimal column definition looks like this:
```php
$this->add([
    'name' => 'Select',
    'construct' => ['name', 'marketplace'],
    'label' => 'Marketplace',
]);
```

### Select

#### GROUP_CONCAT
```php
$this->add([
	'name' => 'Select',
	//'construct' => [new Doctrine\ORM\Query\Expr\\Select("GROUP_CONCAT(serial.number)"), 'serial_all'], // Doctrine usage
	//'construct' => [new Doctrine\ORM\Query\Expr\Func('GROUP_CONCAT', ['serial.number']), 'serial_all'], // Doctrine usage
	'construct' => [new Zend\Db\Sql\Expression ('GROUP_CONCAT(serial.number)'), 'serial_all'], // ZendTable usage
	'label' => 'Serial number',
]);
```

```php
$this->add([
	'name' => 'Select',
	// doctrine doesn't support this expression
	//'construct' => [new Expr\Func('GROUP_CONCAT', ['CASE WHEN serial.cartItem > 0 THEN serial.number ELSE 0 END']), 'serial_id'],
	
	// zend table usage
	'construct' => [new Sql\Expression('GROUP_CONCAT(CASE WHEN serial.cartItemId > 0 THEN serial.number END)'), 'serial_id'],
	'label' => 'Serial number',
]);
```


### Column Data Types
#### Number
The `Number` (original: [`Type\Number`](https://github.com/zfc-datagrid/zfc-datagrid/blob/master/docs/03.%20Columns.md#number)) 
column data types is used to format numbers using the PHP [*NumberFormatter*](http://php.net/manual/en/class.numberformatter.php), 
and so you can use the *NumberFormatter* properties in this data type, to do so you create a `Number` object 
which can takes the following parameters in order:

* Format Style: default `NumberFormatter::DECIMAL`
* Format Type: default `NumberFormatter::TYPE_DEFAULT`
* Locale: Default `Locale::getDefault()`

You can also do the following for this type:

* `'type' => ['prefix' => 'prefix']`
* `'type' => ['suffix' => 'suffix']`
* `'type' => ['attribute' => ['attrName', 'attrValue']]`

A usage Example of this column data type is the following:
```php
$this->add([
    'name' => 'Select',
    'construct' => ['weight', 'product'],
    'label' => 'Weight',
    'type' => [
        'name' => 'Number',
        'attribute' => [\NumberFormatter::FRACTION_DIGITS, 2],
        'suffix' => ' kg'
    ],
]);
```


### Column Data Styles
#### Align
The `Align` is used to change text direction of rows or columns of the grid, to create the `Align` do the following:

```php
$this->add([
    'name' => 'Select',
    'construct' => ['price', 'product'],
    'label' => 'Asin',
    'styles' => [[
        'name' => 'Align',
        'construct' => ['right'],
    ]],
]);
```
or
```php
$this->add([
    'name' => 'Select',
    'construct' => ['price', 'product'],
    'label' => 'Asin',
    'styles' => [[
        'name' => 'Align',
        'construct' => [\ZfcDatagrid\Column\Style\Align::$RIGHT],
    ]],
]);
```

If you set column type as `Number` the text direction automatically will be changed to right
```php
$this->add([
    'name' => 'Select',
    'construct' => ['price', 'product'],
    'label' => 'Asin',
    'type' => [
        'name' => 'Number'
    ],
]);
```

#### BackgroundColor
The `BackgroundColor` is used to change background color of rows or columns of the grid, to create a `BackgroundColor` do the following:
```php
$this->add([
    'name' => 'Select',
    'construct' => ['temperature', 'planet'],
    'label' => 'Temperature',
    'styles' => [[
        'name' => 'BackgroundColor',
        'constuct' => [200, 200, 200]
    ]],
]);
```
where the parameters are the color red green blue values.

To see how to apply style on rows or columns see the [Applying Style](#Applying Style) section.


#### Bold
The `Bold` style simply make the text bold,  you can create a bold style like this:
```php
$this->add([
    'name' => 'Select',
    'construct' => ['name', 'product'],
    'label' => 'Name',
    'styles' => [[
        'name' => 'Bold'
    ]],
]);
```

To see how to apply style on rows or columns see the [Applying Style](#Applying Style) section.


#### Color
The `Color` is used to change the color of rows or columns of the grid, to create a `Color` do the following::
```php
$this->add([
    'name' => 'Select',
    'construct' => ['name', 'product'],
    'label' => 'Name',
    'styles' => [[
        'name' => 'Bold',
        'consturct' => [200, 200, 200]
    ]],
]);
```
where the parameters are the color red green blue values.

To see how to apply style on rows or columns see the [Applying Style](#Applying Style) section.


#### Italic
The `Italic` style simply make the text italic, you can create an Italic style like this:
```php
$this->add([
    'name' => 'Select',
    'construct' => ['name', 'product'],
    'label' => 'Name',
    'styles' => [[
        'name' => 'Italic'
    ]],
]);
```
where the parameters are the color red green blue values.

To see how to apply style on rows or columns see the [Applying Style](#Applying Style) section.

#### Strikethrough
The `Strikethrough` style simply make the text strikethrough, you can create a bold style like this:
```php
$this->add([
    'name' => 'Select',
    'construct' => ['name', 'product'],
    'label' => 'Name',
    'styles' => [[
        'name' => 'Strikethrough'
    ]],
]);
```

To see how to apply style on rows or columns see the [Applying Style](#Applying Style) section.


#### CSSClass
The `CSSClass` is used to set additional classes attribute of rows or cells of the grid, to create a `CSSClass` do the following::
```php
$this->add([
    'name' => 'Select',
    'construct' => ['name', 'product'],
    'label' => 'Name',
    'styles' => [[
        'name' => 'CSSClass',
        'class' => ['text-upper', 'product-name']
    ]],
]);
```


#### Applying Style

- Apply only when the value of the column :product_price: = 50
```php
$this->add([
    'name' => 'Select',
    'construct' => ['price', 'product'],
    'label' => 'Price',
    'styles' => [
        [
            'name' => 'Color',
            'construct' => [\ZfcDatagrid\Column\Style\Color::$RED],
            'byValue' => [[':product_price:', 50, \ZfcDatagrid\Filter::EQUAL]]
        ],
    ],
]);
```

You can add multiple conditions for the style using `ByValue`, and you can set the operator between the multiple conditions to be 'OR' or 'AND' like the following:

- Apply only when the value of the column ':product_price:' between 20 and 40 (inclusive)
```php
$this->add([
    'name' => 'Select',
    'construct' => ['price', 'product'],
    'label' => 'Price',
    'styles' => [
        [
            'name' => 'Color',
            'construct' => [\ZfcDatagrid\Column\Style\Color::$RED],
            'byValueOperator' => 'AND',
            'byValue' => [
                [':product_price:', 20, \ZfcDatagrid\Filter::GREATER_EQUAL],
                [':product_price:', 40, \ZfcDatagrid\Filter::LESS_EQUAL]
            ]
        ],
    ],
]);
```

- Apply only when the value of the column ':order_quantity:' is greater or equal than the value of the column ':product_stock:'
```php
$this->add([
    'name' => 'Select',
    'construct' => ['quantity', 'order'],
    'label' => 'Price',
    'styles' => [
        [
            'name' => 'Color',
            'construct' => [\ZfcDatagrid\Column\Style\Color::$GREEN],
            'byValue' => [[':order_quantity:', ':product_stock:', \ZfcDatagrid\Filter::LESS_EQUAL]]
        ],
    ],
]);
```
> Notice. This functionality is not full tested!


### Column Data Formatters
#### Link
The Link formatters displays a column content as an HTML link with value and href is the column content, to use it do the following:
```php
$this->add([
    'name' => 'Select',
    'construct' => ['asin', 'product'],
    'label' => 'Asin',
    'formatters' => [[
        'name' => 'Link',
        'link' => ['href' => '//www.amazon.de/dp/%s', 'placeholder_column' => 'product_asin']
    ]],
]);
```

You also can pass Column object as 'placeholder_column'
```php
$colId = $this->add([
    'name' => 'Select',
    'construct' => ['id', 'product'],
    'identity' => true,
])->getDataGrid()->getColumnByUniqueId('product_id');
        
$this->add([
    'name' => 'Select',
    'construct' => ['asin', 'product'],
    'label' => 'Asin',
    'formatters' => [[
        'name' => 'Link',
        'link' => ['href' => '//www.amazon.de/dp/%s', 'placeholder_column' => $colId]
    ]],
]);
```

The link formatter also support multiple placeholders for build url
```php
$this->add([
    'name' => 'Select',
    'construct' => ['asin', 'product'],
    'label' => 'Asin',
    'formatters' => [[
        'name' => 'Link',
        'link' => ['href' => '//%s/dp/%s', 'placeholder_column' => ['marketplace_host', 'product_asin']]
    ]],
]);
```

#### Inline
Inline Formatter allow to show text in one line. This will be replace all `<br>`, `\n` and some other filtration for full
compatibility with jqGrid.
```php
$this->add([
    'name' => 'Select',
    'construct' => ['description', 'product'],
    'label' => 'Description',
    'formatters' => [[
        'name' => 'Inline',
    ]],
]);
```

#### ExternalLink
ExternalLink Formatter allow use full URL without inner `rawurlencode` preparation.
> Preference is given to `Link Formatter`. Use in extreme cases.
```php
$this->add([
    'name' => 'Select',
    'construct' => ['url', 'customer'],
    'label' => 'Customer Url',
    'hidden' => true
]);
$this->add([
    'name' => 'Select',
    'construct' => ['name', 'customer'],
    'label' => 'Customer Name',
    'formatters' => [[
        'name' => 'ExternalLink',
        'link' => ['href' => '%s', 'placeholder_column' => 'customer_url']
    ]],
]);
```

### DropDown in search panel
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

### DatePicker in search panel
At this moment DatePicker require partial settings. 
You must carefully monitor the date formats. 
```php
$this->add([
    'name' => 'Select',
    'construct' => ['createdAt', 'question'],
    'label' => 'Date Create',
    'translation_enabled' => false,
    'width' => 1,
    'filter_default_operation' => Filter::LIKE_RIGHT, // LIKE "2018-03-16%"
    'type' => [
        'name' => 'DateTime',
        //'output_pattern' => 'yyyy-MM-dd HH:mm:ss',
        'output_pattern' => 'yyyy-MM-dd',
        'source_dateTime_format' => 'Y-m-d' // this date format will be used in WHERE statment
    ],
    'renderer_parameters' => [
        #['editable', true, 'jqGrid'],
        ['formatter', 'date', 'jqGrid'], // it is important for datepicker
        ['formatoptions', ['srcformat' => 'Y-m-d', 'newformat' => 'Y-m-d'], 'jqGrid'],
        ['searchoptions', ['sopt' => ['eq']], 'jqGrid'],
    ],
]);
```


### Grid Data Sorting

Default grid data sort can be set with `sortDefault` option. 
`ASC` sort order will be applied to column `position` only if any other user filters did not apply before.
```php
$this->add([
    'name' => 'Select',
    'construct' => ['position', 'product'],
    'label' => 'Position',
    'sortDefault' => [1, 'ASC']
]);
```

Also several default sort orders can be set, simply apply `sortDefault` to relative columns
```php
$this->add([
    'name' => 'Select',
    'construct' => ['inStock', 'product'],
    'label' => 'Position',
    'sortDefault' => [1, 'DESC']
]);
```

```php
$this->add([
    'name' => 'Select',
    'construct' => ['position', 'product'],
    'label' => 'Position',
    'sortDefault' => [2, 'ASC']
]);


```
