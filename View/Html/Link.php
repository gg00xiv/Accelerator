<?php

namespace Accelerator\View\Html;

/**
 * Description of Link
 *
 * @author gg00xiv
 */
class Link extends HtmlElement {

    public function __construct($href, $innerHtml, array $attributes = null) {
        parent::__construct('a', array_merge(array('href' => $href), $attributes? : array()));
        $this->setInnerHtml($innerHtml);
    }

    public function getHref() {
        return $this->getAttribute('href');
    }

    public function setHref($href) {
        $this->setAttribute('href', $href);
    }

    public function getTitle() {
        return $this->getAttribute('title');
    }

    public function setTitle($title) {
        $this->setAttribute('title', $title);
    }

    public function getTarget() {
        return $this->getAttribute('target');
    }

    public function setTarget($target) {
        $this->setAttribute('target', $target);
    }

}

?>