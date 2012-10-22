<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of TextBox
 *
 * @author gg00xiv
 */
class TextBox extends FormElement {

    private $isMultilines;

    public function __construct($name, $isMultilines = false, array $attributes = null, $label = null) {
        parent::__construct($isMultilines ? 'textarea' : 'input', array_merge(
                        array('name' => $name), $isMultilines ? array() : array('type' => 'text'), $attributes? : array()), $label);
        $this->isMultilines = $isMultilines;
        if ($this->isMultilines)
            $this->mustCloseTag = true;
    }

    public function isMultilines() {
        return $this->isMultilines;
    }

    public function setMultilines($isMultilines) {
        $this->isMultilines = $isMultilines;
    }

    public function setValue($value) {
        if ($this->isMultilines)
            $this->setInnerHtml($value);
        else if ($value)
            $this->attributes['value'] = $value;
    }

}

?>