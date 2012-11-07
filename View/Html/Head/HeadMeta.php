<?php

namespace Accelerator\View\Html\Head;

/**
 * Description of HeadMeta
 *
 * @author gg00xiv
 */
class HeadMeta extends \Accelerator\View\Html\HtmlElement {

    /**
     * Create HTML head meta tag, like for page description or auto refresh.
     * 
     * @param string $name
     * @param string $content
     */
    public function __construct($name, $content) {
        parent::__construct('meta', array('name' => $name, 'content' => $content));
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
    
    /**
     * Invalidate insertElement calls.
     * 
     * @param int $position
     * @param \Accelerator\View\Html\HtmlElement $element
     * @throws \Accelerator\View\Html\Exception\HtmlException
     */
    public function insertElement($position, \Accelerator\View\Html\HtmlElement $element) {
        throw new \Accelerator\View\Html\Exception\HtmlException('Cannot add child elements to this object.');
    }
}

?>