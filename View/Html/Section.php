<?php

namespace Accelerator\View\Html;

/**
 * Description of Section
 *
 * @author gg00xiv
 */
class Section extends ContainerElement {

    public function __construct(array $attributes = null) {
        parent::__construct('section', $attributes);
    }

}

?>