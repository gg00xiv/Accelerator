<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of RequiredValidator
 *
 * @author gg00xiv
 */
class RequiredValidator extends \Accelerator\Stdlib\Validator {

    public function __construct($msg = null) {
        parent::__construct($msg? : 'Required');
    }

    public function validate($input) {
        return $input ? true : false;
    }

}

?>
