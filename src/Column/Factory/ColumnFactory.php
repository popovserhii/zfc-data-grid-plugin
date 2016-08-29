<?php
/**
 * Flexible Column Factory
 *
 * @category Agere
 * @package Agere_ZfcDataGrid
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 19.12.15 17:44
 */
namespace Agere\ZfcDataGrid\Column\Factory;

use Zend\Stdlib\Exception;
use Zend\Filter\Word\SeparatorToCamelCase;
use Agere\ZfcDataGrid\Service\Plugin\DataGridPluginManager;

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
        $className = $config['name'];
        if (!$cpm->has($config['name'])) {
            throw new Exception\RuntimeException(
                sprintf('%s "%s" not fount in the "data_grid_plugins" key of configuration array', $group, $config['name'])
            );
        }

        /** @var \ZfcDatagrid\Column\Action $object */
        if (isset($config['construct'])) {
            $className = $cpm->getInvokableClass($className);
			//$object = new $className(...$config['construct']);
			
			// @link http://stackoverflow.com/a/2409288/1335142
			if (version_compare(phpversion(), '5.6.0', '>=')) {
				$object = new $className(eval('...') . $config['construct']);
			} else {
				$reflect = new \ReflectionClass($className);
				$object = $reflect->newInstanceArgs($config['construct']);
			}
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
                //\Zend\Debug\Debug::dump([$method, $suffix, __METHOD__.__LINE__]);
                $options = $this->{$method}($value);
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
                    foreach ($value as $a => $v) { // $a -> attribute, $v -> value
                        $object->{$method}($a, $v);
                    }
                } else {
					if (version_compare(phpversion(), '5.6.0', '>=')) {
						$object->{$method}(eval('...') . $value);
					} else {
						call_user_func_array([$object, $method], $value); 
					}
                }
                //\Zend\Debug\Debug::dump($method); die(__METHOD__);
            //} elseif (method_exists($object, $method)) {
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
}
