<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of ComboBox
 *
 * @author gg00xiv
 */
class ComboBox extends FormElement {

    public function __construct($name, $multiple = false, array $attributes = null, $label = null) {
        parent::__construct('select', array_merge(array('multiple' => $multiple ? 'multiple' : '', 'name' => $name), $attributes? : array()), $label);
    }

}

?>
