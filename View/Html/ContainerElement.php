<?php

namespace Accelerator\View\Html;

/**
 * Description of ContainerElement
 *
 * @author gg00xiv
 */
abstract class ContainerElement extends HtmlElement {

    public function __construct($name, array $attributes = null) {
        parent::__construct($name, $attributes);
    }

}

?>