<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of LengthValidator
 *
 * @author gg00xiv
 */
class LengthValidator extends Validator {

    private $min;
    private $max;

    public function __construct($size, $msg = null) {
        if (is_array($size)) {
            if (count($size) != 2)
                throw new \Accelerator\Exception\ArgumentException('$size', 'Must be an array of two values (ex: array(2,5))');
            $this->min = $size[0];
            $this->max = $size[1];
        }else if (is_int($size)) {
            $this->min = 0;
            $this->max = $size;
        } else {
            throw new \Accelerator\Exception\ArgumentException('$size', 'Must be an array of two values or an integer.');
        }

        parent::__construct($msg? : 'Length must be between ' . $this->min . ' and ' . $this->max);
    }

    protected function onValidate($input) {
        return strlen($input) >= $this->min && strlen($input) <= $this->max;
    }

}

?>
