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

use Zend\Filter\Word\CamelCaseToDash;

abstract class DefaultButton
{
    protected $caption = '';

    protected $title = 'New Button';

    protected $icon = 'glyphicon-file';

    protected $position = 100;

    protected $cursor = 'pointer';

    protected $id = null;

    protected $name = '';

    protected $options = [];

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return DefaultButton
     */
    public function setTitle(string $title): DefaultButton
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getCaption(): string
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     * @return DefaultButton
     */
    public function setCaption(string $caption): DefaultButton
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return DefaultButton
     */
    public function setIcon(string $icon): DefaultButton
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return DefaultButton
     */
    public function setPosition(int $position): DefaultButton
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return string
     */
    public function getCursor(): string
    {
        return $this->cursor;
    }

    /**
     * @param string $cursor
     * @return DefaultButton
     */
    public function setCursor(string $cursor): DefaultButton
    {
        $this->cursor = $cursor;

        return $this;
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     * @return DefaultButton
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        if (!$this->name) {
            $parts = explode('\\', get_class($this));
            $name = end($parts);
            $name = lcfirst(str_replace('Button', '', $name));
            $this->name = $name;
        }

        return $this->name;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     * @return DefaultButton
     */
    public function setOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }
}