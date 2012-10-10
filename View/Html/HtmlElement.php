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

    public function render() {
        print('<' . $this->name);
        foreach ($this->attributes as $attrName => $attrValue) {
            print(' ' . $attrName . '="' . $attrValue . '"');
        }
        if ($this->elements) {
            print('>');
            foreach ($this->elements as $element) {
                $element->render();
            }
            print('</' . $this->name . '>');
        } else {
            print(' />');
        }
    }

}

?>
