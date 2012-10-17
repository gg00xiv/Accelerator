<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of SubmitButton
 *
 * @author gg00xiv
 */
class SubmitButton extends FormElement {

    public function __construct($name = null, $displayText = null, array $attributes = null) {
        parent::__construct('input', array_merge(
                        array('type' => 'submit'), $name ? array('name' => $name) : array(), $displayText ?
                                array('value' => $displayText) : array(), $attributes? : array()));
    }

}

?>