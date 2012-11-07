<?php

namespace Accelerator\View\Html\Form;

/**
 * Description of ComboBoxItem
 *
 * @author gg00xiv
 */
class ComboBoxItem extends \Accelerator\View\Html\HtmlElement {

    public function __construct($text, $value = null) {
        parent::__construct('option', $value !== null ? array('value' => $value) : null );
        $this->setInnerHtml($text);
    }

    /**
     * Returns the value of this ComboBoxItem.
     * 
     * @return string
     */
    public function getValue() {
        return $this->attributes['value'];
    }

    /**
     * Returns the displayed text of this ComboBoxItem.
     * 
     * @return string
     */
    public function getText() {
        return $this->getInnerHtml();
    }

    /**
     * Checks whether this ComboBoxItem instance is defined as selected.
     * 
     * @return boolean
     */
    public function isSelected(){
        return isset($this->attributes['selected']);
    }
    
    /**
     * Define this ComboBoxItem instance as selected or not.
     * 
     * @param boolean $selected
     */
    public function setSelected($selected){
        if ($selected)
            $this->setAttribute ('selected', 'selected');
        else
            $this->removeAttribute ('selected');
    }
}

?>
