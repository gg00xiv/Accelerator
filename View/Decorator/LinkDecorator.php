<?php

namespace Accelerator\View\Decorator;

use Accelerator\View\Html\Link;

/**
 * Description of LinkDecorator
 *
 * @author gg00xiv
 */
abstract class LinkDecorator extends \Accelerator\Stdlib\Decorator {

    public abstract function getTitle();

    public abstract function getText();

    public abstract function getPath();

    public function getLink(array $attributes = null) {
        return new Link($this->getPath(), $this->getText(), $attributes);
    }

    public function __toString() {
        return $this->getLink()->__toString();
    }

}

?>
