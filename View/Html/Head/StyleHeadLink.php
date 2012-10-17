<?php

namespace Accelerator\View\Html\Head;

/**
 * Description of StyleHeadLink
 *
 * @author gg00xiv
 */
class StyleHeadLink extends HeadLink {

    public function __construct($href, array $attributes = null) {
        parent::__construct('stylesheet', $href, 'text/css', $attributes);
    }

}

?>