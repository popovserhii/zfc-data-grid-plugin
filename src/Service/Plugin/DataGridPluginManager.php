<?php
/**
 * DataGrid Plugin Manager
 *
 * @category Popov
 * @package Popov_ZfcDataGrid
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @datetime: 09.03.15 21:29
 */
namespace Popov\ZfcDataGridPlugin\Service\Plugin;

use Zend\Stdlib\Exception;
use Zend\ServiceManager\AbstractPluginManager;
use ZfcDatagrid\Column;
use ZfcDatagrid\Column\Type;
use ZfcDatagrid\Column\Style;
use ZfcDatagrid\Column\Action;
use ZfcDatagrid\Column\Formatter;

class DataGridPluginManager extends AbstractPluginManager
{
    protected $sharedByDefault = false;

    /**
     * Default set of extension classes
     * Note: Use config notation for more flexibility
     *
     * @var array
     */
    /*protected $invokableClasses = [
        // column
        'selectcolumn' => Column\Select::class,
        'actioncolumn' => Column\Action::class,
        'externaldatacolumn' => Column\ExternalData::class,

        // action
        'buttonaction' => Action\Button::class,
        'checkboxaction' => Action\Checkbox::class,
        'iconaction' => Action\Icon::class,

        // type
        'imagetype' => Type\Image::class,
        'numbertype' => Type\Number::class,
        'datetimetype' => Type\DateTime::class,
        'phparraytype' => Type\PhpArray::class,
        'phpstringtype' => Type\PhpString::class,

        // style
        'alignstyle' => Style\Align::class,
        'backgroundcolorstyle' => Style\BackgroundColor::class,
        'boldstyle' => Style\Bold::class,
        'colorstyle' => Style\Color::class,
        'cssclassstyle' => Style\CSSClass::class,
        'htmlstyle' => Style\Html::class,
        'italicstyle' => Style\Italic::class,
        'strikethroughstyle' => Style\Strikethrough::class,

        // formatter
        'emailformatter' => Formatter\Email::class,
        'filesizeformatter' => Formatter\FileSize::class,
        'generatelinkformatter' => Formatter\GenerateLink::class,
        'htmltagformatter' => Formatter\HtmlTag::class,
        'imageformatter' => Formatter\Image::class,
        'linkformatter' => Formatter\Link::class,
    ];*/

    public function validate($plugin)
    {
        if ($plugin instanceof DataGridPluginInterface
            || isset($this->factories[get_class($plugin)])
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
        // @todo дістати з aliases клас перевірити чи він є в invokables
        return isset($this->aliases[$name]) ? $this->aliases[$name] : false;
    }
}
