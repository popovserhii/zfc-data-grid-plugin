<?php

namespace Popov\ZfcDataGridPlugin\Column\Formatter;

use ZfcDatagrid\Column\AbstractColumn;
use ZfcDatagrid\Column\Formatter\AbstractFormatter;

class Inline extends AbstractFormatter
{
    /** @var array */
    protected $validRenderers = [
        'jqGrid',
        'bootstrapTable',
    ];

    /**
     * @param AbstractColumn $column
     * @return string
     */
    public function getFormattedValue(AbstractColumn $column)
    {
        $row = $this->getRowData();

        $html = preg_replace('/<br\s?\/?>/i', '', $row[$column->getUniqueId()]);
        $html = preg_replace("/[\r\n]{2,}/", '', $html);
        $html = preg_replace("/>\s+</", '><', $html); // html in one line

        return $html;
    }
}
