<?php

namespace Accelerator\View\Html;

/**
 * Description of InlineElement
 *
 * @author gg00xiv
 */
abstract class InlineElement extends HtmlElement {

    public function __construct($name, array $attributes = null) {
        parent::__construct($name, $attributes);
    }

}

?>