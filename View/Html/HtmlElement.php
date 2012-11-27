<?php

namespace Accelerator\View\Html;

/**
 * Description of HtmlElement
 *
 * @author gg00xiv
 */
class HtmlElement {

    /**
     * The tag name of this element.
     * 
     * @var string 
     */
    protected $name;

    /**
     * The attributes of this element.
     * 
     * @var array
     */
    protected $attributes;

    /**
     * The parent HtmlElement of this instance.
     * 
     * @var \Accelerator\View\Html\HtmlElement
     */
    protected $parent;

    /**
     * Inner elements of this instance.
     * 
     * @var array 
     */
    private $elements;

    /**
     * Inner HTML content of this instance if forced.
     * 
     * @var string 
     */
    protected $innerHtml;

    /**
     * Indicates whethere to close the open tag by a closing tag or just /&gt;
     * 
     * @var boolean 
     */
    protected $mustCloseTag;

    /**
     * Create a HTML element.
     * 
     * @param type $name Element tag name.
     * @param array $attributes Element attributes (like class="..." id="...")
     */
    public function __construct($name, array $attributes = null) {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    /**
     * Get the attribute list as an array(attrName=>attrValue)
     * 
     * @return array
     */
    public function getAttributes() {
        return $this->attributes? : array();
    }

    /**
     * Set an attribute value.
     * 
     * @param string $name
     * @param mixed $value
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;

        return $this;
    }
    
    /**
     * Remove an attribute.
     * 
     * @param string $name
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function removeAttribute($name){
        unset($this->attributes[$name]);
        
        return $this;
    }

    /**
     * Get an attribute value.
     * 
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name) {
        return $this->attributes[$name];
    }

    /**
     * Returns a copy of inner elements.
     * 
     * @return array Array of HtmlElement. 
     */
    public function getElements() {
        return $this->elements? : array();
    }

    /**
     * Clear child element list of this HtmlElement instance.
     * 
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function clearElements() {
        $this->elements = null;
        return $this;
    }

    /**
     * Add HTML elements as child to this instance.
     * 
     * @param array $elements Child HTML elements.
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function addElements(array $elements) {
        foreach ($elements as $element) {
            $this->addElement($element);
        }
        return $this;
    }

    /**
     * Add an HTML element as child to this instance.
     * 
     * @param \Accelerator\View\Html\HtmlElement $element Child HTML element.
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function addElement(HtmlElement $element) {
        $this->insertElement($this->elements ? count($this->elements) : 0, $element);
        return $this;
    }

    /**
     * Add an HTML element as child to this instance at a specified position.
     * 
     * @param type $position
     * @param \Accelerator\View\Html\HtmlElement $element
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function insertElement($position, HtmlElement $element) {
        if (!$this->elements)
            $this->elements = array();
        $element->parent = $this;
        for ($i = count($this->elements) - 1; $i >= $position; $i--) {
            $this->elements[$i + 1] = $this->elements[$i];
        }
        $this->elements[$position] = $element;
        return $this;
    }

    /**
     * Force inner HTML content, removes all existing elements from this instance.
     * 
     * @param string $html HTML content.
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function setInnerHtml($html) {
        $this->innerHtml = $html;
        $this->elements = null;

        return $this;
    }

    /**
     * Get the inner HTML content of this instance. If elements are presents
     * returns the concatenation of each element ->getHtml() call.

     * @return string HTML or null. 
     */
    public function getInnerHtml() {
        if ($this->innerHtml)
            return $this->innerHtml;
        else if ($this->elements && count($this->elements) >= 1) {
            $renderList = array();
            foreach ($this->elements as $element) {
                $renderList[] = $element->getHtml();
            }
            return join('', $renderList);
        }
        return null;
    }

    /**
     * Get the full HTML content (including start and end tags) of this instance.
     * 
     * @return string HTML.
     */
    public function getHtml() {
        $attrList = array();
        if ($this->attributes) {
            foreach ($this->attributes as $attrName => $attrValue) {
                $attrList[] = ' ' . $attrName . '="' . $attrValue . '"';
            }
        }
        $startTag = '<' . $this->name . join('', $attrList);
        $endTag = '</' . $this->name . '>';

        $innerHtml = $this->getInnerHtml();

        return $this->mustCloseTag || $innerHtml ?
                $startTag . '>' . $innerHtml . $endTag :
                $startTag . ' />';
    }

    /**
     * Define a JavaScript code to execute when user click on this HtmlElement.
     * 
     * @param string $jsCode
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function onClick($jsCode) {
        $this->attributes['onclick'] = $jsCode;
        return $this;
    }

    /**
     * Define a JavaScript code to execute when user focus this HtmlElement.
     * 
     * @param type $jsCode
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function onFocus($jsCode) {
        $this->attributes['onfocus'] = $jsCode;
        return $this;
    }

    /**
     * Define a JavaScript code to execute when user leave focus from this HtmlElement.
     * 
     * @param type $jsCode
     * @return \Accelerator\View\Html\HtmlElement
     */
    public function onBlur($jsCode) {
        $this->attributes['onblur'] = $jsCode;
        return $this;
    }
    
    /**
     * Define a placeholder text for current HtmlElement.
     * 
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder){
        $this->attributes['placeholder'] = $placeholder;
        return $this;
    }

    /**
     *
     * @return string Call to ->getHtml().
     */
    public function __toString() {
        return $this->getHtml();
    }

}

?>