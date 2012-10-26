<?php

namespace Accelerator\View\Html;

/**
 * Description of TableRowContainer
 *
 * @author gg00xiv
 */
abstract class TableRowContainer extends HtmlElement {

    protected $mustCloseTag = true;

    public function insertElement($position, HtmlElement $element) {
        if (!$element instanceof TableRow)
            throw new Exception\HtmlException('$element must be an instance of TableRow.');

        parent::insertElement($position, $element);
    }

}

?>
