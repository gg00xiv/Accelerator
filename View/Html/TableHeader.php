<?php

namespace Accelerator\View\Html;

/**
 * Description of TableHeader
 *
 * @author gg00xiv
 */
class TableHeader extends HtmlElement {

    protected $mustCloseTag = true;

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('th', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>
