<?php

namespace Accelerator\View\Html;

/**
 * Description of ScriptLink
 *
 * @author gg00xiv
 */
class ScriptLink extends HtmlElement {

    public function __construct($src, array $attributes = array()) {
        parent::__construct('script', array_merge(array('src' => $src, 'type' => 'text/javascript'), $attributes? : array()));
        $this->setInnerHtml('');
    }

}

?>
