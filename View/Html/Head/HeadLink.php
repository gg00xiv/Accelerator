<?php

namespace Accelerator\View\Html\Head;

/**
 * Description of HeadLink
 *
 * @author gg00xiv
 */
class HeadLink extends \Accelerator\View\Html\HtmlElement {

    /**
     * Create HTML head link like for stylesheet or alternate rss.
     * 
     * @param string $rel
     * @param string $type
     * @param string $href
     * @param array $attributes
     * @throws Accelerator\View\Html\Exception\HtmlException
     */
    public function __construct($rel, $type, $href, array $attributes = null) {
        if (!$rel || !$href)
            throw new \Accelerator\View\Html\Exception\HtmlException('rel and href attributes cannot be empty.');

        parent::__construct('link', array_merge(array('rel' => $rel, 'href' => $href), $type ? array('type' => $type) : array(), $attributes? : array()));
    }

    /**
     * Invalidate setInnerHtml calls.
     * 
     * @param string $html
     * @throws \Accelerator\View\Html\Exception\HtmlException
     */
    public function setInnerHtml($html) {
        throw new \Accelerator\View\Html\Exception\HtmlException('Cannot set inner HTML content for this object.');
    }

}

?>