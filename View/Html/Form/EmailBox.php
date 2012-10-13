<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of EmailBox
 *
 * @author gg00xiv
 */
class EmailBox extends TextBox {

    public function __construct($name, array $attributes = null, $label = null) {
        parent::__construct($name, false, array_merge(array('type' => 'email'), $attributes? : array()), $label);
    }

}

?>
