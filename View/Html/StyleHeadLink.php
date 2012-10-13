<?php

namespace Accelerator\View\Html;

/**
 * Description of StyleHeadLink
 *
 * @author gg00xiv
 */
class StyleHeadLink extends HeadLink {

    public function __construct($href, array $attributes = null) {
        parent::__construct('stylesheet', $href, $attributes);
    }

}

?>
