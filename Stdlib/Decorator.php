<?php

namespace Accelerator\Stdlib;

/**
 * Description of Decorator
 *
 * @author gg00xiv
 */
abstract class Decorator {

    protected $innerObj;

    public function __construct($obj) {
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
