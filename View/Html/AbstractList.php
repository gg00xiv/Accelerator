<?php

namespace Accelerator\View\Html;

/**
 * Description of List
 *
 * @author gg00xiv
 */
abstract class AbstractList extends HtmlElement {

    /**
     * Overrides parent method to allow only ListItem instances for $element parameter.
     * 
     * @param int $position
     * @param \Accelerator\View\Html\HtmlElement $element
     * @throws \Accelerator\Exception\ArgumentException
     */
    public function insertElement($position, HtmlElement $element) {
        if (!$element instanceof ListItem) {
            throw new \Accelerator\Exception\ArgumentException('$element', 'Must be an instance of ListItem.');
        }

        parent::insertElement($position, $element);
    }

}

?>
