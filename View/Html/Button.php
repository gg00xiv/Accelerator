<?php

namespace Accelerator\View\Html;

/**
 * Description of Button
 *
 * @author gg00xiv
 */
class Button extends InlineElement {

    public function __construct(array $attributes = null) {
        parent::__construct('button', $attributes);
    }

}

?>