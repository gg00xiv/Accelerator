<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of EmailValidator
 *
 * @author gg00xiv
 */
class EmailValidator extends RegexValidator {

    public function __construct($msg = null) {
        parent::__construct('/^([\w\.\-_]+@[\w\-\.]+\.\w+)?$/', $msg? : 'Invalid email address');
    }

}

?>
