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

namespace Popov\ZfcDataGridPlugin\Column\Attribute;

use Popov\ZfcCore\Helper\UrlHelper;
use Popov\ZfcDataGridPlugin\Column\Factory\ColumnFactory;

/**
 * Difference between UrlAttribute and LinkAttribute is that first return URL
 * with placeholder which are related to column values
 * and last simply generate URL based on passed params
 */
class UrlAttribute implements AttributeInterface
{
    /**
     * @var ColumnFactory
     */
    protected $columnFactory;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function setColumnFactory($columnFactory)
    {
        $this->columnFactory = $columnFactory;
    }

    public function prepare($object, $params)
    {
        $url = $this->urlHelper->generate($params['route'], $params['params'], $params['options']);

        return $url;
    }
}