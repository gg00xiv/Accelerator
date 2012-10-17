<?php

namespace Accelerator\View\Html;

/**
 * Description of Paragraph
 *
 * @author gg00xiv
 */
class Paragraph extends ContainerElement {

    public function __construct(array $attributes = null) {
        parent::__construct('p', $attributes);
    }

    public function addElement(HtmlElement $element) {
        if ($element instanceof ContainerElement)
            throw new Exception\HtmlException('Cannot add ContainerElement to Paragraph.');
        parent::addElement($element);
    }

}

?>