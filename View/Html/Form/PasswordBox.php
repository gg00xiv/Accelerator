<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of PasswordBox
 *
 * @author gg00xiv
 */
class PasswordBox extends TextBox {

    public function __construct($name, array $attributes = null, $label = null) {
        parent::__construct($name, false, array_merge(array('type' => 'password'), $attributes? : array()), $label);
    }

}

?>
