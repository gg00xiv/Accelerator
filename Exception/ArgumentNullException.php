<?php

namespace Accelerator\Exception;

/**
 * Description of ArgumentNullException
 *
 * @author gg00xiv
 */
class ArgumentNullException extends \Exception {

    public function __construct($argumentName, $message = null) {
        parent::__construct($argumentName . ' argument is missing. ' . $message);
    }

}

?>