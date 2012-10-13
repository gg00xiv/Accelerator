<?php

namespace Accelerator\View\Html\Form;


/**
 * Description of Label
 *
 * @author gg00xiv
 */
class Label extends FormElement {

    public function __construct($text, FormElement $for, array $attributes = null) {
        parent::__construct('label', array_merge(array('for' => $for->getName()), $attributes? : array()));
        $this->setInnerHtml($text);
    }

}

?>
