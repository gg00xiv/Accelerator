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
     */
    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
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
     * Add HTML elements as child to this instance.
     * 
     * @param array $elements Child HTML elements.
     */
    public function addElements(array $elements) {
        foreach ($elements as $element) {
            $this->addElement($element);
        }
    }

    /**
     * Add an HTML element as child to this instance.
     * 
     * @param HtmlElement $element Child HTML element.
     */
    public function addElement(HtmlElement $element) {
        if (!$this->elements)
            $this->elements = array();
        $element->parent = $this;
        $this->elements[] = $element;
    }

    /**
     * Force inner HTML content, removes all existing elements from this instance.
     * 
     * @param string $html HTML content.
     */
    public function setInnerHtml($html) {
        $this->innerHtml = $html;
        $this->elements = null;
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
     *
     * @return string Call to ->getHtml().
     */
    public function __toString() {
        return $this->getHtml();
    }

}

?>