<?php

namespace Accelerator\View\Html\Head;

/**
 * Description of StyleHeadLink
 *
 * @author gg00xiv
 */
class RssHeadLink extends HeadLink {

    public function __construct($href, array $attributes = null) {
        parent::__construct('alternate', $href, 'application/rss+xml', $attributes);
    }

}

?>