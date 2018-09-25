<?php

namespace Popov\ZfcDataGridPlugin\Column\Formatter;

use ZfcDatagrid\Column\AbstractColumn;
use ZfcDatagrid\Column\Formatter;

class ExternalLink extends Formatter\link
{
    /**
     * @param AbstractColumn $col
     * @return string
     */
    protected function getLinkReplaced(AbstractColumn $col)
    {
        $row = $this->getRowData();
        $link = $this->getLink();
        if ($link == '') {
            return $row[$col->getUniqueId()];
        }

        // Replace placeholders
        if (strpos($link, self::ROW_ID_PLACEHOLDER) !== false) {
            $id = '';
            if (isset($row['idConcated'])) {
                $id = $row['idConcated'];
            }
            $link = str_replace(self::ROW_ID_PLACEHOLDER, $id, $link);
        }

        foreach ($this->getLinkColumnPlaceholders() as $col) {
            $link = str_replace(':' . $col->getUniqueId() . ':', $row[$col->getUniqueId()], $link);
        }

        return $link;
    }
}
