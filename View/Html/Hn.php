<?php

namespace Accelerator\View\Html;

/**
 * Description of Hn
 *
 * @author gg00xiv
 */
class Hn extends HtmlElement {

    public function __construct($n, $innerHtml = null, array $attributes = null) {
        if (!$n)
            throw new \Accelerator\Exception\ArgumentNullException('$n');
        parent::__construct('h' . $n, $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>