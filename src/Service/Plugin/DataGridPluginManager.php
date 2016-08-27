<?php
/**
 * DataGrid Plugin Manager
 *
 * @category Agere
 * @package Agere_ZfcDataGrid
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 09.03.15 21:29
 */
namespace Agere\ZfcDataGrid\Service\Plugin;

use Zend\Stdlib\Exception;
use Zend\ServiceManager\AbstractPluginManager;
use ZfcDatagrid\Column;
use ZfcDatagrid\Column\Type;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Action;

class DataGridPluginManager extends AbstractPluginManager
{
    /**
     * Default set of extension classes
     * Note: Use config notation for more flexibility
     *
     * @var array
     */
    protected $invokableClasses = [
        // column
        'select' => Column\Select::class,
        'action' => Column\Action::class,
        'externaldata' => Column\ExternalData::class,

        // action
        'button' => Action\Button::class,
        'checkbox' => Action\Checkbox::class,
        'icon' => Action\Icon::class,

        // type
        'image' => Type\Image::class,
        'number' => Type\Number::class,
        'datetime' => Type\DateTime::class,
        'phparray' => Type\PhpArray::class,
        'phpstring' => Type\PhpString::class,

        // style
        'align' => Style\Align::class,
        'backgroundcolor' => Style\BackgroundColor::class,
        'bold' => Style\Bold::class,
        'color' => Style\Color::class,
        'cssclass' => Style\CSSClass::class,
        'html' => Style\Html::class,
        'italic' => Style\Italic::class,
        'strikethrough' => Style\Strikethrough::class,
    ];

    public function validatePlugin($plugin)
    {
        if ($plugin instanceof DataGridPluginInterface
            || in_array(get_class($plugin), $this->invokableClasses)
        ) {
            // we're okay
            return;
        }
        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement %s\DataGridPluginInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin)),
            __NAMESPACE__
        ));
    }

    public function getInvokableClass($name)
    {
        return $this->invokableClasses[$this->canonicalizeName($name)];
    }
}
