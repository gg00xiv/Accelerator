<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of RegexValidator
 *
 * @author gg00xiv
 */
class RegexValidator extends Validator {

    private $validationPattern;

    public function __construct($validationPattern, $msg = null) {
        parent::__construct($msg? : 'Invalid field value');
        $this->validationPattern = $validationPattern;
    }

    protected function onValidate($input) {
        return preg_match($this->validationPattern, $input);
    }

}

?>
