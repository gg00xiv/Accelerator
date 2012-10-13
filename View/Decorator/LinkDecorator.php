<?php

namespace Accelerator\View\Decorator;

use Accelerator\Decorator;
use Accelerator\View\Html\Link;

/**
 * Description of LinkDecorator
 *
 * @author gg00xiv
 */
abstract class LinkDecorator extends Decorator {

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
