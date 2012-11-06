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

    public function getValue() {
        return $this->attributes['value'];
    }

    public function getText() {
        return $this->getInnerHtml();
    }

}

?>
