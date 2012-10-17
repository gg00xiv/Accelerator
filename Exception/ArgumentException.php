<?php

namespace Accelerator\Exception;

/**
 * Description of ArgumentException
 *
 * @author gg00xiv
 */
class ArgumentException extends \Exception {

    public function __construct($argumentName, $message = null) {
        parent::__construct($argumentName . ' is invalid. ' . $message);
    }

}

?>