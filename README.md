# ZF2 DataGrid Plugin Module

ZF2 DataGrid Plugin Module based on [ZfcDatagrid](https://github.com/ThaDafinser/ZfcDatagrid) and is a kind of superstructure. 
Its main goal to reduce using complexity and improve code readability.

This module register new ```data_grid_plugins``` global config key and add ```ColumnFactory```.

Working principle is using ZF2 way like ```Zend\Form``` which use array configuration for create form elements.

## Usage
Register Plugin. For this move content of ```vendor/agerecompany/zfc-data-grid/config/application.config.php.sample``` in global ```config/application.config.php```

In general is need create new Grid class which will be response for concrete Grid in your ecosystem.

```php
namespace Agere\Invoice\Block\Grid;

use Agere\Grid\Block\AbstractGrid;

class InvoiceGrid extends AbstractGrid
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
      	'styles' => [[
      		'name' => 'BackgroundColor',
      		'construct' => [[224, 226, 229]],
      	]],
      	'formatters' => [[
      		'name' => 'Link',
      		'attributes' => ['class' => 'pencil-edit-icon'],
      		'link' => ['href' => '/invoice/view', 'placeholder_column' => $colId] // special config
      	]],
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
        'formatters' => [[
      	  'name' => 'Barcode',
      	  'source_attr' => 'data-barcode',
      	  //'placeholder_column' => $grid->getColumnByUniqueId('product_id'),
      	  'attributes' => [
      		  'class' => 'barcode-icon'
      	  ],
        ]]
      ]);
	}
}
```
