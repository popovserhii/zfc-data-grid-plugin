<?php
/**
 * Plugin Factory
 *
 * @category Agere
 * @package Agere_ZfcDataGrid
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 09.03.15 21:55
 */
namespace Agere\ZfcDataGrid\Service\Plugin;

use Zend\Mvc\Service\AbstractPluginManagerFactory;

class DataGridPluginFactory extends AbstractPluginManagerFactory
{
    const PLUGIN_MANAGER_CLASS = DataGridPluginManager::class;
}