<?php

namespace Accelerator\View\Html;

/**
 * Description of TableRow
 *
 * @author gg00xiv
 */
class TableRow extends HtmlElement {

    protected $mustCloseTag = true;

    public function __construct(array $columnValues = null, array $attributes = null) {
        parent::__construct('tr', $attributes);
        if (is_array($columnValues)) {
            foreach ($columnValues as $value) {
                $cell = new TableCell($value);
                $this->addElement($cell);
            }
        }
    }

    public function insertElement($position, HtmlElement $element) {
        if (!$element instanceof TableCell && !$element instanceof TableHeader)
            throw new Exception\HtmlException('$element must be an instance of TableCell, TableHeader.');

        parent::insertElement($position, $element);
    }

}

?>
