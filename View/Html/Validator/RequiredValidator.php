<?php

namespace Accelerator\View\Html\Validator;

/**
 * Description of RequiredValidator
 *
 * @author gg00xiv
 */
class RequiredValidator extends Validator {

    public function __construct($msg = null) {
        parent::__construct($msg? : 'Required');
    }

    public function validate($input) {
        return $input ? true : false;
    }

}

?>
