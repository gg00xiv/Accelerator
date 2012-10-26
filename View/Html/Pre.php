<?php

namespace Accelerator\View\Html;

/**
 * Description of Pre
 *
 * @author gg00xiv
 */
class Pre extends HtmlElement {

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('pre', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>
