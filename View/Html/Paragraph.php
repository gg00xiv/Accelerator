<?php

namespace Accelerator\View\Html;

/**
 * Description of Paragraph
 *
 * @author gg00xiv
 */
class Paragraph extends HtmlElement {

    public function __construct(array $attributes = null) {
        parent::__construct('p', $attributes);
    }

}

?>