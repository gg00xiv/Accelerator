<?php

namespace Accelerator\View\Html;

/**
 * Description of TableCell
 *
 * @author gg00xiv
 */
class TableCell extends HtmlElement {

    protected $mustCloseTag = true;

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('td', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>
