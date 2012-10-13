<?php

namespace Accelerator\View\Html\Validator;

/**
 * Description of IntegerValidator
 *
 * @author gg00xiv
 */
class IntegerValidator extends RegexValidator {

    public function __construct($msg = null) {
        parent::__construct('/^\d*$/', $msg? : 'Invalid number');
    }

}

?>
