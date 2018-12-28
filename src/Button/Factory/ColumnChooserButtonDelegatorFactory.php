<?php

namespace Popov\ZfcDataGridPlugin\Button\Factory;

use Popov\ZfcCore\Helper\UrlHelper;
use Popov\ZfcDataGridPlugin\Button\ColumnChooserButton;
use Psr\Container\ContainerInterface;

class ColumnChooserButtonDelegatorFactory
{
    public function __invoke(ContainerInterface $container, $name, callable $callback, array $options = null)
    {
        /** @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);

        $opts['editUrl'] = $urlHelper->generate('admin/default', [
            'controller' => 'data-grid', 'action' => 'buttons'
        ], ['force_canonical' => true]);

        /** @var ColumnChooserButton $columnChooser */
        $columnChooser = $callback();
        $columnChooser->setOptions($opts);

        return $columnChooser;
    }
}