<?php

namespace Accelerator\View\Html;

/**
 * Description of HtmlElement
 *
 * @author gg00xiv
 */
abstract class HtmlElement {

    private $name;
    protected $attributes;
    protected $elements;
    protected $innerHtml;

    public function __construct($name, array $attributes = array()) {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    public function addElements(array $elements) {
        foreach ($elements as $element) {
            if (!$element instanceof HtmlElement)
                throw new \Accelerator\AcceleratorException('Invalid element found.');
            $this->addElement($element);
        }
    }

    public function addElement(HtmlElement $element) {
        if (!$this->elements)
            $this->elements = array();
        $this->elements[] = $element;
    }

    public function setInnerHtml($html) {
        $this->innerHtml = $html;
    }

    public function getInnerHtml() {
        $attrList = array();
        foreach ($this->attributes as $attrName => $attrValue) {
            $attrList[] = ' ' . $attrName . '="' . $attrValue . '"';
        }
        $startTag = '<' . $this->name . join('', $attrList);
        $endTag = '</' . $this->name . '>';
        if ($this->innerHtml || $this->innerHtml === '') {
            return $startTag . '>' . $this->innerHtml . $endTag;
        } else if ($this->elements && count($this->elements) >= 1) {
            $renderList = array();
            foreach ($this->elements as $element) {
                $renderList[] = $element->getInnerHtml();
            }
            return $startTag . '>' . join('', $renderList) . $endTag;
        } else {
            return $startTag . ' />';
        }
    }

}

?>
