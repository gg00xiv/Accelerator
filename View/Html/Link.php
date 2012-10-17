<?php

namespace Accelerator\View\Html;

/**
 * Description of Link
 *
 * @author gg00xiv
 */
class Link extends InlineElement {

    public function __construct($href, $text, array $attributes = null) {
        parent::__construct('a', array_merge(array('href' => $href), $attributes? : array()));
        $this->setInnerHtml($text);
    }

}

?>