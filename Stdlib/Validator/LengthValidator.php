<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of LengthValidator
 *
 * @author gg00xiv
 */
class LengthValidator extends \Accelerator\Stdlib\Validator {

    private $min;
    private $max;

    public function __construct($size, $msg = null) {
        parent::__construct($msg? : 'Invalid input size.');

        if (is_array($size)) {
            if (count($size) != 2)
                throw new \Accelerator\Exception\ArgumentException('$size', 'Must be an array of two values (ex: array(2,5))');
            $this->min = $size[0];
            $this->max = $size[1];
        }else {
            $this->min = 0;
            $this->max = $size;
        }
    }

    public function validate($input) {
        return strlen($input) >= $this->min && strlen($input) <= $this->max;
    }

}

?>
