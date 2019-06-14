<?php

/**
 * The MIT License (MIT)
 * Copyright (c) 2019 Serhii Popov
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

namespace Popov\ZfcDataGridPlugin\View\Helper\Factory;

use Popov\ZfcDataGridPlugin\Service\Plugin\DataGridPluginManager;
use Popov\ZfcDataGridPlugin\Summarizer\Summarizer;
use Popov\ZfcDataGridPlugin\View\Helper\SummarizerHelper;
use Psr\Container\ContainerInterface;

class SummarizerHelperFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $summarizer = $container->get(Summarizer::class);

        return new SummarizerHelper($summarizer);
    }
}