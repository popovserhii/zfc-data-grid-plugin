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
 * @package Popov_ZfcDataGrid
 * @author Serhii Popov <popow.serhii@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */

namespace Popov\ZfcDataGridPlugin\Button;

class ColumnChooserButton extends DefaultButton
{
    protected $title = 'Column Chooser';

    /**
     * @todo Use iconSet.inlinedit.icon_cancel_nav for compatibility with styleUI change
     * @see https://stackoverflow.com/questions/53120312/how-to-change-group-of-css-items-in-jqgrid
     */
    protected $icon = 'fa-table';
}