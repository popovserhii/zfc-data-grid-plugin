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

namespace Popov\ZfcDataGridPlugin\Button;

class ColumnChooserButton extends DefaultButton
{
    protected $caption = '';

    //protected $icon = "ui-icon-calendar";
    protected $icon = "glyphicon-list-alt";

    protected $title = 'Column Chooser';

<<<<<<< HEAD
    protected $options = [];
=======
    //    protected $options = [
    //        'title' => 'Select columns',
    //        'width' => 500,
    //        'height' => 400,
    //        'classname' => null,
    //        'done' => '', //Function which will be called when the user press Ok button.
    //        'msel' => 'multiselect',
    //        'dlog' => 'dialog',
    //        'dlog_opts' => '',
    //        'cleanup' => '',
    //    ];
    protected $options = [
        'width' => 550,
        'dialog_opts' => [
            'modal' => true,
            'minWidth' => 470,
            'height' => 470,
            'show' => 'blind',
            'hide' => 'explode',
            'dividerLocation' => 0.5,
        ],
    ];

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options): void
    {
        foreach ($options as $option => $value) {
            if ($this->options[$option] && is_array($this->options[$option])) {
                foreach ($value as $subOpt => $subOptVal) {
                    if ($this->options[$option][$subOpt]) {
                        $this->options[$option][$subOpt] = $subOptVal;
                    }
                }
            } elseif ($this->options[$option]) {
                $this->options[$option] = $value;
            }
        }
    }
>>>>>>> f4ea09826dfb9231bf3bf46c4f58c3429ed05969
}