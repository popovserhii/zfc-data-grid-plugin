<?php
namespace Popov\ZfcDataGridPlugin;

use ZfcDatagrid;
use ZfcDatagrid\Filter;
use ZfcDatagrid\Column\Type;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Action;
use ZfcDatagrid\Column\Formatter;

return [
    // Middleware way
    'dependencies' => [
        'aliases' => [
            'DataGridPluginManager' => Service\Plugin\DataGridPluginManager::class,
        ],
        'factories' => [
            Service\Plugin\DataGridPluginManager::class => Service\Factory\DataGridPluginManagerFactory::class,
        ],
    ],

    'data_grid_plugins' => [
        'aliases' => [
            // column
            'selectcolumn' => ZfcDatagrid\Column\Select::class,
            'SelectColumn' => ZfcDatagrid\Column\Select::class,
            'actioncolumn' => ZfcDatagrid\Column\Action::class,
            'ActionColumn' => ZfcDatagrid\Column\Action::class,
            'externaldatacolumn' => ZfcDatagrid\Column\ExternalData::class,
            'ExternalDataColumn' => ZfcDatagrid\Column\ExternalData::class,

            // action
            'buttonaction' => Action\Button::class,
            'ButtonAction' => Action\Button::class,
            'CheckboxAction' => Action\Checkbox::class,
            'iconaction' => Action\Icon::class,
            'IconAction' => Action\Icon::class,

            // type
            'imagetype' => Type\Image::class,
            'ImageType' => Type\Image::class,
            'numbertype' => Type\Number::class,
            'datetimetype' => Type\DateTime::class,
            'DateTimeType' => Type\DateTime::class,
            'phparraytype' => Type\PhpArray::class,
            'PhpArrayType' => Type\PhpArray::class,
            'phpstringtype' => Type\PhpString::class,
            'PhpStringType' => Type\PhpString::class,

            // style
            'alignstyle' => Style\Align::class,
            'AalignStyle' => Style\Align::class,
            'backgroundcolorstyle' => Style\BackgroundColor::class,
            'BackgroundColorStyle' => Style\BackgroundColor::class,
            'boldstyle' => Style\Bold::class,
            'BoldStyle' => Style\Bold::class,
            'colorstyle' => Style\Color::class,
            'ColorStyle' => Style\Color::class,
            'cssclassstyle' => Style\CSSClass::class,
            'CssClassStyle' => Style\CSSClass::class,
            'htmlstyle' => Style\Html::class,
            'HtmlStyle' => Style\Html::class,
            'italicstyle' => Style\Italic::class,
            'ItalicStyle' => Style\Italic::class,
            'strikethroughstyle' => Style\Strikethrough::class,
            'StrikeThroughStyle' => Style\Strikethrough::class,

            // formatter
            'emailformatter' => Formatter\Email::class,
            'EmailFormatter' => Formatter\Email::class,
            'filesizeformatter' => Formatter\FileSize::class,
            'FileSizeFormatter' => Formatter\FileSize::class,
            'generatelinkformatter' => Formatter\GenerateLink::class,
            'GenerateLinkFormatter' => Formatter\GenerateLink::class,
            'htmltagformatter' => Formatter\HtmlTag::class,
            'HtmlTagFormatter' => Formatter\HtmlTag::class,
            'imageformatter' => Formatter\Image::class,
            'ImageFormatter' => Formatter\Image::class,
            'linkformatter' => Formatter\Link::class,
            'LinkFormatter' => Formatter\Link::class,
        ],

        'invokables' => [
            // column
            ZfcDatagrid\Column\Select::class => ZfcDatagrid\Column\Select::class,
            ZfcDatagrid\Column\Action::class => ZfcDatagrid\Column\Action::class,
            ZfcDatagrid\Column\ExternalData::class => ZfcDatagrid\Column\ExternalData::class,

            // action
            Action\Icon::class => Action\Icon::class,
            Action\Button::class => Action\Button::class,
            Action\Checkbox::class => Action\Checkbox::class,

            // type
            Type\Image::class => Type\Image::class,
            Type\Number::class => Type\Number::class,
            Type\DateTime::class => Type\DateTime::class,
            Type\PhpArray::class => Type\PhpArray::class,
            Type\PhpString::class => Type\PhpString::class,

            // style
            Style\Html::class => Style\Html::class,
            Style\Bold::class => Style\Bold::class,
            Style\Align::class => Style\Align::class,
            Style\Color::class => Style\Color::class,
            Style\Italic::class => Style\Italic::class,
            Style\CSSClass::class => Style\CSSClass::class,
            Style\Strikethrough::class => Style\Strikethrough::class,
            Style\BackgroundColor::class => Style\BackgroundColor::class,

            // formatter
            Formatter\Link::class => Formatter\Link::class,
            Formatter\Image::class => Formatter\Image::class,
            Formatter\Email::class => Formatter\Email::class,
            Formatter\HtmlTag::class => Formatter\HtmlTag::class,
            Formatter\FileSize::class => Formatter\FileSize::class,
            Formatter\GenerateLink::class => Formatter\GenerateLink::class,
        ],

        //'factories' => [
        //    Type\DateTime::class => Column\Type\Factory\DateTimeFactory::class,
        //],

        //'abstract_factories' => []
    ],

    'data_grid_plugins_config' => [
        Type\DateTime::class => [
            'output_pattern' => 'yyyy-MM-dd HH:mm',
            'source_date_time_format' => 'Y-m-d H:i',
            'source_timezone' => ini_get('date.timezone'),
        ],
        'type_of' => [ // setting for Column with relative Type
            Type\DateTime::class => [
                'renderer_parameters' => [
                    ['formatter', 'date', 'jqGrid'], // it important for datepicker
                    ['formatoptions', ['srcformat' => 'Y-m-d H:i', 'newformat' => 'Y-m-d H:i'], 'jqGrid'],
                    ['sorttype', 'date', 'jqGrid'],
                    ['searchoptions', ['sopt' => ['eq']], 'jqGrid'],
                ],
            ]
        ]
    ]
];