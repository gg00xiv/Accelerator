<?php

namespace Accelerator\View\Html;

/**
 * Description of Button
 *
 * @author gg00xiv
 */
class Button extends HtmlElement {

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('button', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>