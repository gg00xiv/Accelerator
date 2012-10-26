<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of ComboBox
 *
 * @author gg00xiv
 */
class ComboBox extends FormElement {

    private $items;

    public function __construct($name, $multiple = false, array $attributes = null, $label = null) {
        parent::__construct('select', array_merge($multiple ? array('multiple' => '') : array(), array('name' => $name), $attributes? : array()), $label);
        $this->items = array();
    }

    public function addElement(\Accelerator\View\Html\HtmlElement $element) {
        if (!$element instanceof ComboBoxItem)
            throw new \Accelerator\Exception\ArgumentException('$element', 'Invalid type, only ComboBoxItem instance allowed.');

        parent::addElement($element);
    }

    public function setValue($value) {
        foreach ($this->getElements() as $element) {
            if ($element->getValue() == $value) {
                $element->setAttribute('selected', '');
                return;
            }
        }
    }

    public function addItem($text, $value = null) {
        $this->addElement(new ComboBoxItem($text, $value));
    }

    public function addItems(array $items) {
        foreach ($items as $text => $value)
            $this->addItem($text, $value);
    }

}

?>
