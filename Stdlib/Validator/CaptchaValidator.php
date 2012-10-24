<?php

namespace Accelerator\Stdlib\Validator;

/**
 * Description of CaptchaValidator
 *
 * @author gg00xiv
 */
class CaptchaValidator extends Validator {

    /**
     *
     * @var Accelerator\Captcha\Captcha
     */
    private $captcha;

    /**
     * Create a validator based on a Captcha derivated class.
     * 
     * @param \Accelerator\Captcha\Captcha $captcha
     * @param string $msg
     * @throws \Accelerator\Exception\ArgumentNullException
     */
    public function __construct(\Accelerator\Captcha\Captcha $captcha, $msg = null) {
        if (!$captcha)
            throw new \Accelerator\Exception\ArgumentNullException('$captcha');

        $this->captcha = $captcha;

        parent::__construct($msg? : 'Invalid captcha');
    }

    protected function onValidate($input) {
        return $this->captcha->isValid($input);
    }

}

?>
