<?php

namespace Accelerator\Model;

/**
 * Description of DbEntityCollection
 *
 * @author gg00xiv
 */
class DbEntityCollection extends \ArrayObject {

    public function toHtmlTable() {

        $table = new \Accelerator\View\Html\Table();
        if (count($this) == 0)
            return $table;

        $first = true;
        foreach ($this as $entity) {
            if (!$entity instanceof DbEntity)
                throw new \Accelerator\Exception\AcceleratorException('DbEntityCollection can only contains DbEntity objects.');
            $columnValues = $entity->getColumnValues();
            if ($first) {
                $table->addElement(new \Accelerator\View\Html\TableHead(array_keys($columnValues)));
                $first = false;
            }
            $values = array_map(function($value) {
                        return htmlspecialchars($value);
                    }, $columnValues);
            $table->addRow($values);
        }

        return $table;
    }

    public function first() {
        return $this[0];
    }
    
}

?>
