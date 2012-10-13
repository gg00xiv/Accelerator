<?php

namespace Accelerator\View\Html\Validator;

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

    public function validate($input) {
        return preg_match($this->validationPattern, $input);
    }

}

?>
