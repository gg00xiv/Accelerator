<?php

namespace Accelerator\View\Decorator;

/**
 * Description of LinkDecorator
 *
 * @author gg00xiv
 */
abstract class LinkDecorator {

    protected abstract function getTitle();

    protected abstract function getText();

    protected abstract function getPath();

    public function getLink($classes = null) {
        return '<a title="' . $this->getTitle() . '"' .
                ( is_array($classes) && count($classes) >= 1 ?
                        ' class="' . join(' ', $classes) . '"' :
                        is_string($classes) ? ' class="' . $classes . '"' : '') .
                ' href="' . rtrim(\Accelerator\Application::instance()->getBaseUrl(), '/') . '/' .
                ltrim($this->getPath(), '/') . '">' . $this->getText() . '</a>';
    }

}

?>
