<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of Validator
 *
 * @author gg00xiv
 */
abstract class Validator {

    protected function __construct($msg) {
        $this->msg = $msg;
    }

    /**
     * Validate string given in parameter.
     * 
     * @param string $input
     */
    public abstract function validate($input);

    public function getMessage() {
        return $this->msg;
    }

}

?>
