<?php
/**
 * Column DateTime Type
 *
 * @category Popov
 * @package Popov_ZfcDataGridPlugin
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 02.12.2016 11:38
 */
namespace Popov\ZfcDataGridPlugin\Column\Type\Factory;

use Interop\Container\ContainerInterface;
use ZfcDatagrid\Column\Type\DateTime;

class DateTimeFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $sm = $container->getServiceLocator();
        $config = $sm->get('Config');
        //$fem = $sm->get('FormElementManager');
        //$statusService = $sm->get('StatusService');
        //$progressService = $sm->get('ProgressService');
        //$statusChanger = $sm->get('StatusChanger');

        return (new DateTime())->setServiceManager($sm);
    }
}