<?php

namespace Accelerator\View\Decorator;

use Accelerator\Decorator;

/**
 * Description of LinkDecorator
 *
 * @author gg00xiv
 */
abstract class LinkDecorator extends Decorator {

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

    public function __toString() {
        return $this->getLink();
    }

}

?>
