<?php

namespace Popov\ZfcDataGridPlugin\View\Helper;

use Popov\ZfcDataGridPlugin\Summarizer\Summarizer;
use Zend\View\Helper\AbstractHelper;

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

class SummarizerHelper extends AbstractHelper
{
    /** @var Summarizer */
    protected $summarizer;

    public function __construct(Summarizer $summarizer)
    {
        $this->summarizer = $summarizer;
    }

    public function getSummarizer()
    {
        return $this->summarizer;
    }

    public function __invoke()
    {
        return $this;
    }
}