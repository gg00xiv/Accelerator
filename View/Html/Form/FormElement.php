<?php

namespace Accelerator\View\Html\Form;

use Accelerator\View\Html\HtmlElement;
use Accelerator\View\Html\Validator\Validator;

/**
 * Description of FormElement
 *
 * @author gg00xiv
 */
abstract class FormElement extends HtmlElement {

    private $_label;
    private $_validators;

    public function __construct($name, array $attributes = array(), $label = null) {
        parent::__construct($name, $attributes);
        $this->setLabel($label);
    }

    public function getName() {
        return $this->attributes['name'];
    }

    /**
     * Define a label HTML element for this form element and return it.
     * 
     * @param string $text Label text content or Label instance.
     * @return \Accelerator\View\Html\Form\FormElement The current form element.
     */
    public function setLabel($text) {
        if ($text) {
            if ($text instanceof Label)
                $this->_label = $text;
            else if (is_string($text))
                $this->_label = new Label($text, $this);

            return $this->_label;
        }
        return null;
    }

    public function getLabel() {
        return $this->_label;
    }

    public function getValue() {
        switch ($this->parent->getMethod()) {
            case Form::METHOD_GET:
                return $_GET[$this->getName()];
            case Form::METHOD_POST:
                return $_POST[$this->getName()];
        }
    }

    public function setValue($value) {
        
    }

    public function addValidator(Validator $validator) {
        if (!$this->_validators)
            $this->_validators = array();
        $this->_validators[] = $validator;
    }

    public function isValid() {
        if (isset($this->_isValid))
            return $this->_isValid;

        $this->_isValid = true;

        if (!$this->parent->isPostBack())
            return true;

        if ($this->_validators) {
            foreach ($this->_validators as $validator)
                if (!$validator->validate($this->getValue())) {
                    $this->_isValid = false;
                    break;
                }
        }

        return $this->_isValid;
    }

    public function getHtml() {
        if ($this->parent->isPostBack()) {
            $this->setValue($this->getValue());
        }
        $output = parent::getHtml();
        if (!$this->isValid()) {
            foreach ($this->_validators as $validator) {
                if (!$validator->validate($this->getValue())) {
                    $msg = $validator->getMessage();
                    $template = $this->parent->getValidationTemplate();
                    $output.=$template ?
                            str_replace(':error', $validator->getMessage(), $template) :
                            $msg;
                }
            }
        }
        return $output;
    }

}

?>
