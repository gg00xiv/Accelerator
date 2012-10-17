<?php

namespace Accelerator\View\Html\Head;

/**
 * Description of HeadMeta
 *
 * @author gg00xiv
 */
class HeadMeta extends \Accelerator\View\Html\HtmlElement {

    public function __construct($name, $content) {
        parent::__construct('meta', array('name' => $name, 'content' => $content));
    }

}

?>