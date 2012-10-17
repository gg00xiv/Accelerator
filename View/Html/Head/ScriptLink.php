<?php

namespace Accelerator\View\Html\Head;

/**
 * Description of ScriptLink
 *
 * @author gg00xiv
 */
class ScriptLink extends \Accelerator\View\Html\HtmlElement {

    public function __construct($src, array $attributes = null) {
        parent::__construct('script', array_merge(array('src' => $src, 'type' => 'text/javascript'), $attributes? : array()));
        $this->setInnerHtml('');
    }

}

?>
