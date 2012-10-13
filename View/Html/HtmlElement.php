<?php

namespace Accelerator\View\Html;

/**
 * Description of HtmlElement
 *
 * @author gg00xiv
 */
abstract class HtmlElement {

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
    private $_elements;

    /**
     * Inner HTML content of this instance if forced.
     * 
     * @var string 
     */
    protected $innerHtml;

    /**
     * Create a HTML element.
     * 
     * @param type $name Element tag name.
     * @param array $attributes Element attributes (like class="..." id="...")
     */
    public function __construct($name, array $attributes = array()) {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    /**
     * Returns a copy of inner elements.
     * 
     * @return array Array of HtmlElement. 
     */
    public function getElements() {
        return $this->_elements? : array();
    }

    /**
     * Add HTML elements as child to this instance.
     * 
     * @param array $elements Child HTML elements.
     * @throws \Accelerator\AcceleratorException If at least one element is not a HtmlElement instance.
     */
    public function addElements(array $elements) {
        foreach ($elements as $element) {
            if (!$element instanceof HtmlElement)
                throw new \Accelerator\AcceleratorException('Invalid element found.');
            $this->addElement($element);
        }
    }

    /**
     * Add an HTML element as child to this instance.
     * 
     * @param HtmlElement $element Child HTML element.
     */
    public function addElement(HtmlElement $element) {
        if (!$this->_elements)
            $this->_elements = array();
        $element->parent = $this;
        $this->_elements[] = $element;
    }

    /**
     * Force inner HTML content, removes all existing elements from this instance.
     * 
     * @param string $html HTML content.
     */
    public function setInnerHtml($html) {
        $this->innerHtml = $html;
        $this->_elements = null;
    }

    /**
     * Get the inner HTML content of this instance. If elements are presents
     * returns the concatenation of each element ->getHtml() call.

     * @return string HTML or null. 
     */
    public function getInnerHtml() {
        if ($this->innerHtml || $this->innerHtml === '')
            return $this->innerHtml;
        else if ($this->_elements && count($this->_elements) >= 1) {
            $renderList = array();
            foreach ($this->_elements as $element) {
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

        return $innerHtml === null ?
                $startTag . ' />' :
                $startTag . '>' . $innerHtml . $endTag;
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
