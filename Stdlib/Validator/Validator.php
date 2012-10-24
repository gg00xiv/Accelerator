<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of Validator
 *
 * @author gg00xiv
 */
abstract class Validator {

    private $validatedInputs;

    protected function __construct($msg) {
        $this->msg = $msg;
        $this->validatedInputs = new \Accelerator\Cache\MemoryCache();
    }

    /**
     * Validate string given in parameter.
     * 
     * @param string $input
     */
    public function validate($input) {
        if (($isValid = $this->validatedInputs->get($input)) === null) {
            $isValid = $this->onValidate($input);
            $this->validatedInputs->put($input, $isValid);
        }

        return $isValid;
    }

    protected abstract function onValidate($input);

    public function getMessage() {
        return $this->msg;
    }

}

?>
