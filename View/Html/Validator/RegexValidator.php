<?php

namespace Accelerator\View\Html\Validator;

/**
 * Description of RegexValidator
 *
 * @author gg00xiv
 */
class RegexValidator extends Validator {

    private $validationPattern;

    public function __construct($validationPattern) {
        $this->validationPattern = $validationPattern;
    }

    public function validate($input) {
        
    }

}

?>
