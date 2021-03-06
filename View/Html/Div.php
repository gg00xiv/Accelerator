<?php

namespace Accelerator\View\Html;

/**
 * Description of Div
 *
 * @author gg00xiv
 */
class Div extends HtmlElement {

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('div', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>