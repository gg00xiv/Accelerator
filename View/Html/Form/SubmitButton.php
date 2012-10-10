<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of SubmitButton
 *
 * @author gg00xiv
 */
class SubmitButton extends FormElement {

    public function __construct(Form $form, $name, $displayText = null, array $attributes = array()) {
        parent::__construct($form, 'input', array_merge(
                        array('type' => 'submit', 'name' => $name, 'value' => $displayText), $attributes));
    }

}

?>
