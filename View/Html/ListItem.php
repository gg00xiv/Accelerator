<?php

namespace Accelerator\View\Html;

/**
 * Description of ListItem
 *
 * @author gg00xiv
 */
class ListItem extends HtmlElement {

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('li', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>
