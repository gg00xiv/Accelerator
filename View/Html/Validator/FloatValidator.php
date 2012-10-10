<?php

namespace Accelerator\View\Html\Validator;

/**
 * Description of FloatValidator
 *
 * @author gg00xiv
 */
class FloatValidator extends RegexValidator {

    public function __construct($decimalSeparator = '.') {
        parent::__construct('\d+(\\' . $decimalSeparator . '\d+)?');
    }

}

?>
