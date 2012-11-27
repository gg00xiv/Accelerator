<?php

namespace Accelerator\View\Html;

/**
 * Description of Span
 *
 * @author gg00xiv
 */
class Span extends HtmlElement {

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('span', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>