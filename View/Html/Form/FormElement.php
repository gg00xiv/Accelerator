<?php

namespace Accelerator\View\Html\Form;

use Accelerator\View\Html\HtmlElement;

/**
 * Description of FormElement
 *
 * @author gg00xiv
 */
abstract class FormElement extends HtmlElement {

    public function __construct($name, array $attributes = array()) {
        parent::__construct($name, $attributes);
    }

    public function getName() {
        return $this->attributes['name'];
    }
    /**
     * Define a label HTML element on this form element.
     * 
     * @param string $text Label text content.
     * @return \Accelerator\View\Html\Form\FormElement The current form element.
     */
    public function setLabel($text) {
        return new Label($text, $this);
    }

}

?>
