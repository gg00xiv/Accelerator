<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of StringValidator
 *
 * @author gg00xiv
 */
class StringValidator extends Validator {

    private $validationStrings;

    public function __construct($validationString, $msg = null) {
        parent::__construct($msg? : 'Invalid value');
        $this->validationStrings = is_array($validationString) ? $validationString : array($validationString);
    }

    protected function onValidate($input) {
        return in_array($input, $this->validationStrings);
    }

}

?>
