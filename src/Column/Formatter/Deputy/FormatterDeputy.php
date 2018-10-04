<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2018 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Popov
 * @package Popov_<package>
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcDataGridPlugin\Column\Formatter\Deputy;

use ZfcDatagrid\Column\AbstractColumn;

class FormatterDeputy
{
    public function delegate(AbstractColumn $column, array $formatters, array $configs)
    {
        $delegateConfig = [];
        foreach ($formatters as $i => $formatter) {
            $config = $configs[$i];
            $delegateName = lcfirst($config['name']);
            $delegateConfig['chain'][] = $delegateName;
            foreach ($config as $name => $value) {
                if ('name' == $name) {
                    continue;
                }
                $fetched = $formatter->{'get' . ucfirst($name)}();
                // By default jqGrid can use only one Formatter.
                // Current multiple formatters support is experimental implementation
                // and developer should use it on his own risk.
                $delegateConfig[$delegateName][$name] = $fetched;
            }
        }

        if ($delegateConfig) {
            $column->setRendererParameter('formatter', 'chain', 'jqGrid');
            $column->setRendererParameter('formatoptions', $delegateConfig, 'jqGrid');
        }
    }
}