<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of FormElement
 *
 * @author gg00xiv
 */
abstract class FormElement extends \Accelerator\View\Html\InlineElement {

    private $label;
    private $validators;
    private $isValuePersistent = true;
    private $errorMessage = null;

    public function __construct($name, array $attributes = null, $label = null) {
        parent::__construct($name, $attributes);
        $this->setLabel($label);
    }

    public function getName() {
        return $this->attributes['name'];
    }

    public function isValuePersistent() {
        return $this->isValuePersistent;
    }

    public function setValuePersistency($persistency) {
        $this->isValuePersistent = $persistency;
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
                $this->label = $text;
            else if (is_string($text))
                $this->label = new Label($text, $this);

            return $this->label;
        }
        return null;
    }

    public function getLabel() {
        return $this->label;
    }

    public function getValue() {
        switch ($this->parent->getMethod()) {
            case Form::METHOD_GET:
                $value = $_GET[$this->getName()];
                break;
            case Form::METHOD_POST:
                $value = $_POST[$this->getName()];
                break;
        }
        return stripslashes($value);
    }

    public function setValue($value) {
        
    }

    public function addValidator(\Accelerator\Stdlib\Validator\Validator $validator) {
        if (!$this->validators)
            $this->validators = array();
        $this->validators[] = $validator;
    }

    public function setErrorMessage($msg) {
        $this->errorMessage = $msg;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function isValid() {
        if (isset($this->_isValid))
            return $this->_isValid;

        $this->_isValid = true;

        if (!$this->parent->isPostBack())
            return true;

        if ($this->errorMessage) {
            $this->_isValid = false;
        } else if ($this->validators) {
            foreach ($this->validators as $validator)
                if (!$validator->validate($this->getValue())) {
                    $this->_isValid = false;
                    break;
                }
        }

        return $this->_isValid;
    }

    private function getErrorHtml($msg) {
        $template = $this->parent->getValidationTemplate();
        return $template ?
                str_replace(':error', $msg, $template) :
                $msg;
    }

    public function getHtml() {
        if ($this->parent->isPostBack() && $this->isValuePersistent) {
            $this->setValue($this->getValue());
        }
        $output = parent::getHtml();
        if (!$this->isValid()) {
            if ($this->errorMessage) {
                $output.=$this->getErrorHtml($this->errorMessage);
            } else {
                foreach ($this->validators as $validator) {
                    if (!$validator->validate($this->getValue())) {
                        $msg = $validator->getMessage();
                        $output.=$this->getErrorHtml($msg);
                    }
                }
            }
        }

        return $output;
    }

}

?>
