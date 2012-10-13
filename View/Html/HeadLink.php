<?php

namespace Accelerator\View\Html;

use Accelerator\AcceleratorException;

/**
 * Description of HeadLink
 *
 * @author gg00xiv
 */
class HeadLink extends HtmlElement {

    public function __construct($rel, $href, array $attributes = null) {
        if (!$rel || !$href)
            throw new AcceleratorException('rel and href attributes cannot be empty.');

        parent::__construct('link', array_merge(array('rel' => $rel, 'href' => $href), $attributes? : array()));
    }

    public function setInnerHtml($html) {
        throw new AcceleratorException('Cannot set inner HTML content in HeadLink object.');
    }

}

?>
