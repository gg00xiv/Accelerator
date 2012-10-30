<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of Form
 *
 * @author gg00xiv
 */
class Form extends \Accelerator\View\Html\HtmlElement {

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    private $successHtml = null;
    private $validationTemplate;

    public function __construct($method = null, array $attributes = null) {
        parent::__construct('form', $attributes);

        if ($method)
            $this->setMethod($method);
        else if (is_array($attributes) && !array_key_exists('method', $attributes))
            $this->setMethod(self::METHOD_GET);
    }

    /**
     * 
     * @param int $position
     * @param \Accelerator\View\Html\HtmlElement $element FormElement element.
     */
    public function insertElement($position, \Accelerator\View\Html\HtmlElement $element) {
        if ($element instanceof FormElement) {
            $label = $element->getLabel();
            if ($label) {
                parent::insertElement($position++, $label);
            }
        }
        parent::insertElement($position, $element);
    }

    /**
     * Set the method used for data transmission.
     * 
     * @param string $method method name from Form::METHOD_xxx consts.
     * @throws \Accelerator\View\Html\Exception\HtmlException If method name is not known.
     */
    public function setMethod($method) {
        if (!in_array($method, array(self::METHOD_GET, self::METHOD_POST))) {
            throw new \Accelerator\View\Html\Exception\HtmlException('Invalid method : ' . $method);
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
     * @throws \Accelerator\View\Html\Exception\HtmlException If field name doesn't exists.
     */
    public function getValue($name) {
        foreach ($this->getElements() as $element) {
            if (!$element instanceof FormElement)
                continue;
            if ($element->getName() == $name)
                return $element->getValue();
        }
        throw new \Accelerator\View\Html\Exception\HtmlException('Value not found for : ' . $name);
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
     * Check if current request is a postback
     * 
     * @return boolean 
     */
    public function isPostBack() {
        return $_SERVER['REQUEST_METHOD'] == $this->getMethod();
    }

    /**
     * Mixed call of isPostBack and isValid.
     * $this->isPostBackIsValid() is equivalent to : $this->isPostBack() && $this->isValid()
     * 
     * @return boolean
     */
    public function isPostBackValid() {
        return $this->isPostBack() && $this->isValid();
    }

    /**
     * When form is successfully submitted with no error, display the $html in place.
     * 
     * @param string $html 
     */
    public function setSuccessHtml($html) {
        $this->successHtml = $html;
    }

    /**
     * Define the validation message template when input error occurs.
     * 
     * @param string $template 
     */
    public function setValidationTemplate($template) {
        $this->validationTemplate = $template;
    }

    /**
     * Returns the validation message template when input error occurs.
     * 
     * @return string 
     */
    public function getValidationTemplate() {
        return $this->validationTemplate;
    }

    /**
     * Display the form in current state.
     * 
     * @return string HTML content.
     */
    public function getHtml() {
        if ($this->isPostBack() && $this->isValid() && $this->successHtml !== null)
            return $this->successHtml;

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
            $html.='<li>' . $fieldName . ' : ' . str_replace("\n", '<br/>', $fieldValue) . '</li>';
        }
        $html.='</ul>';
        \Accelerator\Helper\MailHelper::sendHtml($to, $fromName, $fromAddress, $subject, $html);
    }

}

?>
