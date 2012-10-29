<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of StringValidator
 *
 * @author gg00xiv
 */
class StringValidator extends Validator {

    private $validationString;

    public function __construct($validationString, $msg = null) {
        parent::__construct($msg? : 'Invalid value');
        $this->validationString = $validationString;
    }

    protected function onValidate($input) {
        return $input == $this->validationString;
    }

}

?>
