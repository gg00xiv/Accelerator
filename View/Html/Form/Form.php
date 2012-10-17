<?php

namespace Accelerator\View\Html\Form;

use Accelerator\View\Html\HtmlElement;
use Accelerator\AcceleratorException;
use Accelerator\Helper\MailHelper;

/**
 * Description of Form
 *
 * @author gg00xiv
 */
class Form extends HtmlElement {

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    private $_successHtml = false;
    private $_validationTemplate;

    public function __construct(array $attributes = array()) {
        parent::__construct('form', $attributes);

        $this->setMethod(array_key_exists('method', $attributes) ? $attributes['method'] : self::METHOD_GET);
    }

    /**
     * Override addElement of HtmlElement class to restrict adding element to
     * FormElement elements.
     * 
     * @param HtmlElement $element FormElement element.
     * @throws AcceleratorException If $element is not instance of FormElement.
     */
    public function addElement(HtmlElement $element) {
        if (!$element instanceof FormElement)
            throw new AcceleratorException('Only FormElement objects can be added to Form.');
        $label = $element->getLabel();
        if ($label) {
            parent::addElement($label);
        }
        parent::addElement($element);
    }

    /**
     * Set the method used for data transmission.
     * 
     * @param string $method method name from Form::METHOD_xxx consts.
     * @throws \Accelerator\AcceleratorException If method name is not known.
     */
    public function setMethod($method) {
        if (!in_array($method, array(self::METHOD_GET, self::METHOD_POST))) {
            throw new AcceleratorException('Invalid method : ' . $method);
        }

        $this->attributes['method'] = $method;
    }

    /**
     * Get the current method used for data transmission.
     * 
     * @return string Among Form::METHOD_xxx consts. 
     */
    public function getMethod() {
        return $this->attributes['method'];
    }

    /**
     * Get the field value from current request post back.
     * 
     * @param string $name Field name.
     * @return mixed Field value.
     * @throws \Accelerator\AcceleratorException If field name doesn't exists.
     */
    public function getValue($name) {
        foreach ($this->getElements() as $element) {
            if (!$element instanceof FormElement)
                continue;
            if ($element->getName() == $name)
                return $element->getValue();
        }
        throw new AcceleratorException('Value not found for : ' . $name);
    }

    /**
     * Get all field values from current request post back.
     * You should call isValid method before getting field values.
     * 
     * @return array Array of field name => value. 
     */
    public function getValues() {
        $values = array();
        foreach ($this->getElements() as $element) {
            if (!$element instanceof FormElement || !$element->getName())
                continue;
            $values[$element->getName()] = $element->getValue();
        }
        return $values;
    }

    /**
     * Validate a form by validating each of its fields.
     * 
     * @return boolean 
     */
    public function isValid() {
        if (isset($this->_isValid))
            return $this->_isValid;

        $this->_isValid = true;

        if (!$this->isPostBack())
            return true;

        foreach ($this->getElements() as $element) {
            if (!$element->isValid()) {
                $this->_isValid = false;
                break;
            }
        }

        return $this->_isValid;
    }

    /**
     * Check if current request is a postback on the same page.
     * 
     * @return boolean 
     */
    public function isPostBack() {
        return $_SERVER['HTTP_REFERER'] == $_SERVER['SCRIPT_URI'];
    }

    /**
     * When form is successfully submitted, display the $html in place.
     * 
     * @param string $html 
     */
    public function setSuccessHtml($html) {
        $this->_successHtml = $html;
    }

    /**
     * Define the validation message template when input error occurs.
     * 
     * @param string $template 
     */
    public function setValidationTemplate($template) {
        $this->_validationTemplate = $template;
    }

    /**
     * Returns the validation message template when input error occurs.
     * 
     * @return string 
     */
    public function getValidationTemplate() {
        return $this->_validationTemplate;
    }

    /**
     * Display the form in current state.
     * 
     * @return string HTML content.
     */
    public function getHtml() {
        if ($this->isPostBack() && $this->isValid() && ($this->_successHtml || $this->_successHtml === ''))
            return $this->_successHtml;
        return parent::getHtml();
    }

    /**
     * Send the current form content by email.
     * 
     * @param type $to Destination email address.
     * @param type $fromName Sender name.
     * @param type $fromAddress Sender email address.
     * @param type $subject Email subject.
     */
    public function sendByMail($to, $fromName, $fromAddress, $subject) {
        $html = '<p>Form content :</p>';
        $html . '<ul>';
        foreach ($this->getValues() as $fieldName => $fieldValue) {
            $html.='<li>' . $fieldName . ' : ' . $fieldValue . '</li>';
        }
        $html.='</ul>';
        MailHelper::sendHtml($to, $fromName, $fromAddress, $subject, $html);
    }

}

?>
