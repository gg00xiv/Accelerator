<?php

namespace Accelerator\View\Html;

/**
 * Description of Article
 *
 * @author gg00xiv
 */
class Article extends HtmlElement {

    public function __construct($innerHtml = null, array $attributes = null) {
        parent::__construct('article', $attributes);
        $this->setInnerHtml($innerHtml);
    }

}

?>