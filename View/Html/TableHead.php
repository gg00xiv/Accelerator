<?php

namespace Accelerator\View\Html;

/**
 * Description of TableHead
 *
 * @author gg00xiv
 */
class TableHead extends TableRowContainer {

    public function __construct(array $columnNames = null, array $attributes = null) {
        parent::__construct('thead', $attributes);
        if (is_array($columnNames)) {
            $row = new TableRow();
            $this->addElement($row);
            foreach ($columnNames as $columnName) {
                $row->addElement(new TableCell($columnName));
            }
        }
    }

}

?>
