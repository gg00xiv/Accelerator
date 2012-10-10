<?php

namespace Accelerator\View\Html\Form;

use Accelerator\View\Html\HtmlElement;

/**
 * Description of Label
 *
 * @author gg00xiv
 */
class Label extends HtmlElement {

    public function __construct($text, FormElement $for, array $attributes = array()) {
        parent::__construct('label', array_merge(array('for' => $for->getName()), $attributes));
    }

}

?>
