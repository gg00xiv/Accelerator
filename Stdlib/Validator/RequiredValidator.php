<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of RequiredValidator
 *
 * @author gg00xiv
 */
class RequiredValidator extends Validator {

    public function __construct($msg = null) {
        parent::__construct($msg? : 'Required');
    }

    protected function onValidate($input) {
        return $input ? true : false;
    }

}

?>
