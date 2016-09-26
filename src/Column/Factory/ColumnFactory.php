<?php
/**
 * Flexible Column Factory
 *
 * @category Agere
 * @package Agere_ZfcDataGrid
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 19.12.15 17:44
 */
namespace Agere\ZfcDataGridPlugin\Column\Factory;

use Zend\Stdlib\Exception;
use Zend\Filter\Word\SeparatorToCamelCase;
use ZfcDatagrid\Column\Formatter;
use Agere\ZfcDataGridPlugin\Service\Plugin\DataGridPluginManager;

class ColumnFactory
{
    /** @var array */
    protected $config = [];

    protected $columnPluginManager;

    public function __construct(DataGridPluginManager $columnPluginManager)
    {
        $this->columnPluginManager = $columnPluginManager;
    }

    public function getDataGridPluginManager()
    {
        return $this->columnPluginManager;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function create($config)
    {
        $column = $this->doCreate($config, 'Column');

        return $column;
    }

    /**
     * Get standardizes config key
     *
     * @param $key
     * @return string
     */
    public function getConfigKey($key)
    {
        return strtolower(preg_replace("/[^A-Za-z0-9]/", '', $key));
    }

    protected function doCreate($config, $group)
    {
        if (!isset($config['name']) || !$config['name']) {
            throw new Exception\RuntimeException($group . ' "name" key must be set and cannot be empty');
        }

        $cpm = $this->getDataGridPluginManager();

        //$className = is_array($config['name']) ? $config['name']['name'] : $config['name'];
        $className = $config['name'] . $group;
        if (!$cpm->has($className)) {
            throw new Exception\RuntimeException(sprintf(
                '%s "%s" not fount in the "data_grid_plugins" key of configuration array', $group, $config['name']
            ));
        }

        /** @var \ZfcDatagrid\Column\Action $object */
        if (isset($config['construct'])) {
            $className = $cpm->getInvokableClass($className);
            //$object = new $className(...$config['construct']);

            // @link http://stackoverflow.com/a/2409288/1335142
            //if (version_compare(phpversion(), '7.0.0', '>=')) {
            //	$object = new $className(eval('...') . $config['construct']);
            //} else {
            $reflect = new \ReflectionClass($className);
            $object = $reflect->newInstanceArgs($config['construct']);
            //}
        } else {
            $object = $cpm->get($className);
        }

        $filter = new SeparatorToCamelCase('_');
        foreach ($config as $key => $value) {
            if (in_array($key, ['name', 'construct'])) {
                continue;
            }

            $suffix = ucfirst($filter->filter($key));
            // create sub objects: action, formatter etc.
            if (method_exists($this, $method = 'create' . $suffix)) {
                $options = $this->{$method}($value);
                //\Zend\Debug\Debug::dump([$suffix, $options, __METHOD__.__LINE__]);
                $object->{'set' . $suffix}($options);

                continue;
            }

            $method = 'set' . $suffix;
            if (is_array($value)) {
                if (!method_exists($object, $method)) {
                    // setter for array options like attributes which need be set peer iteration
                    $suffix = substr($suffix, 0, strlen($suffix) - 1); // to singular (remove "s")
                    if (!method_exists($object, $setMethod = $method = 'set' . $suffix)) {
                        if (!method_exists($object, $addMethod = $method = 'add' . $suffix)) {
                            throw new Exception\BadMethodCallException(sprintf(
                                'Neither %s method nor %s method found in class %s',
                                $setMethod,
                                $addMethod,
                                get_class($object)
                            ));
                        }
                    }

                    //\Zend\Debug\Debug::dump([$method, $value]); //die(__METHOD__);


                    foreach ($value as $attribute => $val) {
                        if (is_array($val)) {
                            call_user_func_array([$object, $method], $val);
                        } else {
                            $object->{$method}($attribute, $val);
                        }
                    }


                } else {
                    // prepare special attribute like link or etc.
                    if (method_exists($this, $prepareMethod = 'prepareAttribute' . $suffix)) {
                        $value = $this->{$prepareMethod}($object, $value);
                    }

                    //if (version_compare(phpversion(), '5.6.0', '>=')) {
                    //	$object->{$method}(eval('...') . $value);
                    //} else {
                    //call_user_func_array([$object, $method], $value);
                    //}

                    //\Zend\Debug\Debug::dump([$method, $value]); //die(__METHOD__);
                    if (is_array($value)) {
                        call_user_func_array([$object, $method], $value);
                    } else {
                        $object->{$method}($value);
                    }
                }
                //\Zend\Debug\Debug::dump($method); die(__METHOD__);
            } else {
                $object->{$method}($value);
            }
        }


        return $object;
    }

    public function createActions($config)
    {
        $actions = [];
        foreach ($config as $actionConfig) {
            $actions[] = $this->doCreate($actionConfig, 'Action');
        }

        return $actions;
    }

    public function createFormatters($config)
    {
        $formatters = [];
        foreach ($config as $formatterConfig) {
            $formatters[] = $this->doCreate($formatterConfig, 'Formatter');
        }

        return $formatters;
    }

    public function createStyles($config)
    {
        $styles = [];
        foreach ($config as $styleConfig) {
            $styles[] = $this->doCreate($styleConfig, 'Style');
        }

        return $styles;
    }

    public function createType($config)
    {
        /** @var \ZfcDatagrid\Column\Type\Number $type */
        $type = $this->doCreate($config, 'Type');

        return $type;
    }

    /**
     * Prepare "link" attribute value based on special array configuration
     *
     * Config key:
     *      href - not changed link path
     *      placeholder_column - Column object for get placeholder value
     *
     * @param Formatter\Link $formatter
     * @param $param
     * @return string
     */
    public function prepareAttributeLink(Formatter\Link $formatter, $param)
    {
        if (!is_array($param)) {
            return $param;
        }

        return $param['href'] . '/' . $formatter->getColumnValuePlaceholder($param['placeholder_column']);
    }
}
