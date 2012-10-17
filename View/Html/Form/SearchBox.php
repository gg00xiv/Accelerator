<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of SearchBox
 *
 * @author gg00xiv
 */
class SearchBox extends TextBox {

    public function __construct($name, array $attributes = null, $label = null) {
        parent::__construct($name, false, array_merge(array('type' => 'search'), $attributes? : array()), $label);
    }

}

?>