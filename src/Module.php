<?php

namespace Popov\ZfcDataGridPlugin;

use Zend\ModuleManager\ModuleManager;
use Popov\ZfcDataGridPlugin\Service\Plugin\DataGridPluginProviderInterface;

class Module
{
    public function getConfig()
    {
        $config = include __DIR__ . '/../config/module.config.php';
        $config['service_manager'] = $config['dependencies'];
        unset($config['dependencies']);

        return $config;
    }

    public function init(ModuleManager $moduleManager)
    {
        $container = $moduleManager->getEvent()->getParam('ServiceManager');
        $serviceListener = $container->get('ServiceListener');
        $serviceListener->addServiceManager(
        // The name of the plugin manager as it is configured in the service manager,
        // all config is injected into this instance of the plugin manager.
            'DataGridPluginManager',
            // The key which is read from the merged module.config.php files, the
            // contents of this key are used as services for the plugin manager.
            'data_grid_plugins',
            // The interface which can be specified on a Module class for injecting
            // services into the plugin manager, using this interface in a Module
            // class is optional and depending on how your autoloader is configured
            // it may not work correctly.
            DataGridPluginProviderInterface::class,
            // The function specified by the above interface, the return value of this
            // function is merged with the config from 'sample_plugins_config_key'.
            'getDataGridPluginConfig'
        );
    }

}
