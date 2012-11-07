<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of ComboBox
 *
 * @author gg00xiv
 */
class ComboBox extends FormElement {

    public function __construct($name, $multiple = false, array $attributes = null, $label = null, array $items = null) {
        parent::__construct('select', array_merge($multiple ? array('multiple' => '') : array(), array('name' => $name), $attributes? : array()), $label);
        if ($items)
            $this->addItems($items);
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

    /**
     * Add new item to this ComboBox instance.
     * 
     * @param string $text
     * @param string $value
     * @return \Accelerator\View\Html\Form\ComboBoxItem
     */
    public function addItem($text, $value = null) {
        $this->addElement($item = new ComboBoxItem($text, $value));
        return $item;
    }

    /**
     * Add new items to this ComboBox instance.
     * 
     * @param array $items Must be an associative array.
     * @return \Accelerator\View\Html\Form\ComboBox
     */
    public function addItems(array $items) {
        foreach ($items as $text => $value)
            $this->addItem($text, $value);
        return $this;
    }

    /**
     * Fill this ComboBox instance with result of DbEntity filter.
     * 
     * @param \Accelerator\Model\DbEntity $filter
     * @param string $textField
     * @param string $valueField
     * @return \Accelerator\View\Html\Form\ComboBox
     */
    public function populate(\Accelerator\Model\DbEntity $filter, $textField, $valueField) {
        $entities = $filter->select();
        foreach ($entities as $entity)
            $this->addItem($entity->$textField, $entity->$valueField);
        return $this;
    }

    /**
     * Define a JavaScript code to execute when user change selected element on this HtmlElement.
     * 
     * @param string $jsCode
     * @return \Accelerator\View\Html\Form\ComboBox
     */
    public function onChange($jsCode) {
        $this->attributes['onchange'] = $jsCode;
        return $this;
    }

}

?>
