<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of TextBox
 *
 * @author gg00xiv
 */
class TextBox extends FormElement {

    public function __construct($name, $multipleLines = false, array $attributes = array()) {
        parent::__construct($multipleLines ? 'textarea' : 'input', array_merge(
                        array('name' => $name), $multipleLines ? array() : array('type' => 'text'), $attributes));
        if ($multipleLines)
            parent::setInnerHtml('');
    }

}

?>
