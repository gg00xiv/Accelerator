<?php

namespace Accelerator\Exception;

/**
 * Handles any \Exception into Accelerator framework.
 *
 * @author gg00xiv
 */
class AcceleratorException extends \Exception {
    public function __construct($message) {
        parent::__construct($message);
    }
}

?>
