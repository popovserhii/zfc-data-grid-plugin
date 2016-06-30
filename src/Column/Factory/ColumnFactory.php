<?php
/**
 * Flexible Column Factory
 *
 * @category Agere
 * @package Agere_Grid
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 19.12.15 17:44
 */
namespace Agere\Grid\Column\Factory;

use Zend\Stdlib\Exception;
use Zend\Filter\Word\SeparatorToCamelCase;
use Agere\Grid\Service\Plugin\DataGridPluginManager;

class ColumnFactory
{
    /** @var array */
    protected $config = [];

    /** @var array */
    /*protected $columns = [
        'Select' => Column\Select::class,
        'Action' => Column\Action::class,
        'ExternalData' => Column\ExternalData::class,
    ];*/

    protected $columnPluginManager;

    public function __construct(DataGridPluginManager $columnPluginManager)
    {
        //\Zend\Debug\Debug::dump(get_class($cpm)); die(__METHOD__);

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
        /*$config = [
            //'name' => 'Action',
            //'name' => ['Select' => 'product_id'],
            'type' => 'Select',
            'construct' => ['id', 'product'],
            //'uniqueId' => 'action',
            'label' => ' ',
            'actions' => [
                [
                    'type' => 'AddToCart',
                    'attributes' => [
                        'class' => 'btn btn-primary',
                        'title' => 'Order item',
                    ]
                ]
            ],
            'formatters' => [
                [
                    'type' => 'Barcode',
                    'attributes' => [
                        'class' => 'barcode-icon'
                    ],
                    //'based_on' => $colProductId->getUniqueId()
                ]
            ]

        ];*/

        //$cpm = $this->getColumnPluginManager();

        //$table = isset($config['select']['table']) ? $config['select']['table'] : null;
        //$instance = new $class($config['select']['column'], $table);


        //$config['name'] = isset($config['name']) ? ucfirst($config['name']) : 'Select';
        /** @var \ZfcDatagrid\Column\Select $column */
        /** @var \ZfcDatagrid\Column\Action $column */
        $column = $this->doCreate($config, 'Column');

        //\Zend\Debug\Debug::dump([get_class($column), $column->getUniqueId(), $column->getFormatters()]); die(__METHOD__);

        return $column;

        /*if (!$cpm->has($columnName)) {
            throw new Exception\RuntimeException(
                sprintf('Column "%s" not fount in the "column_plugins" key of configuration array', $columnName)
            );
        }

        $column = $cpm->get($columnName);

        foreach ($config as $key => $value) {
            if ('name' === $key) {
                continue;
            }

            if ('actions' === $key) {
                foreach ($value as $actionConfig) {
                    $action = $this->create($actionConfig);
                    $column->addAction($action);
                }
            }

            if (is_string($value) || is_numeric($value)) {
                $setter = 'set' . ucfirst($key);
                $column->{$setter}($value);
            }

        }*/

        /*$taskKey = $this->getConfigKey($configType);
        if (!isset($this->config['tasks'][$taskKey])) {
            throw new Exception\RuntimeException(
                sprintf('Import task "%s" (alias:%s) not registered', $taskKey, $configType)
            );
        }*/

        //$config = $this->config['tasks'][$taskKey];


        /*if (!isset($config['driver'])) {
            throw new Exception\RuntimeException('Driver key must be set in the configuration array');
        }

        $driverKey = strtolower($config['driver']);
        if (isset($this->columns[$driverKey])) {
            $driverClass = $this->columns[$driverKey];
        } elseif (isset($this->config['class'])) {
            $driverClass = $this->config['class'];
        } else {
            throw new Exception\RuntimeException('Any driver not registered for ' . $driverKey);
        }

        $config += isset($this->config['driver_options'][$driverKey])
            ? $this->config['driver_options'][$driverKey]
            : [];

        $driver = new $driverClass($config);

        return $driver;*/
    }

    /**
     * Get standardizes config key
     *
     * @param $key
     * @return string
     */
    protected function getConfigKey($key)
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
                sprintf('%s "%s" not fount in the "column_plugins" key of configuration array', $group, $config['name'])
            );
        }

        /** @var \ZfcDatagrid\Column\Action $object */
        if (isset($config['construct'])) {
            $className = $cpm->getInvokableClass($className);
            $object = new $className(...$config['construct']);
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
                                'Neither %s method nor %s method fount in class %s',
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
                    $object->{$method}(...$value);
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
