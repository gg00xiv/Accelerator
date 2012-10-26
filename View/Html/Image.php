<?php

namespace Accelerator\View\Html;

/**
 * Description of Image
 *
 * @author gg00xiv
 */
class Image extends HtmlElement {

    public function __construct(array $attributes = null) {
        parent::__construct('img', $attributes);
    }

}

?>
