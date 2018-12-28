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
 * @package Popov_ZfcDataGridPlugin
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcDataGridPlugin\Column\Attribute\Factory;

use Psr\Container\ContainerInterface;
use Popov\ZfcCore\Helper\UrlHelper;
use Popov\ZfcDataGridPlugin\Column\Attribute\UrlAttribute;

class UrlAttributeFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $urlHelper = $container->get(UrlHelper::class);
        $urlAttribute = new UrlAttribute($urlHelper);

        return $urlAttribute;
    }
}