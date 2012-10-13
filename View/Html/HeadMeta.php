<?php

namespace Accelerator\View\Html;

/**
 * Description of HeadMeta
 *
 * @author gg00xiv
 */
class HeadMeta extends HtmlElement {

    public function __construct($name, $content) {
        parent::__construct('meta', array('name' => $name, 'content' => $content));
    }

}

?>
