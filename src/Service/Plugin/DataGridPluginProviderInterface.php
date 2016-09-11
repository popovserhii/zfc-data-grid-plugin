<?php
/**
 * DataGrid Plugin Provider Interface
 *
 * @category Agere
 * @package Agere_Grid
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 09.03.15 22:50
 */
namespace Agere\ZfcDataGridPlugin\Service\Plugin;

interface DataGridPluginProviderInterface
{
    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getDataGridPluginConfig();
}