<?php

namespace Accelerator\View\Html;

/**
 * Description of Script
 *
 * @author gg00xiv
 */
class Script extends HtmlElement {

    protected $mustCloseTag = true;

    public function __construct($src = null, array $attributes = null) {
        parent::__construct('script', array_merge(array('type' => 'text/javascript'), $src ? array('src' => $src) : array(), $attributes? : array()));
    }

}

?>
