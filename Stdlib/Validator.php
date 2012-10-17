<?php

namespace Accelerator\Stdlib;

/**
 * Description of Validator
 *
 * @author gg00xiv
 */
abstract class Validator {

    protected function __construct($msg) {
        $this->msg = $msg;
    }

    public abstract function validate($input);

    public function getMessage() {
        return $this->msg;
    }

}

?>
