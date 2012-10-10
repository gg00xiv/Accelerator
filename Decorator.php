<?php

namespace Accelerator;

/**
 * Description of Decorator
 *
 * @author gg00xiv
 */
abstract class Decorator {

    protected $innerObj;

    protected function __construct($obj) {
        $this->innerObj = $obj;
    }

    public function __get($name) {
        return $this->innerObj->$name;
    }

    public function __set($name, $value) {
        $this->innerObj->$name = $value;
    }

}

?>
