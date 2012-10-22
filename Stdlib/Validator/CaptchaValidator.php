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

    public function __construct($captcha, $msg = null) {
        if (!$captcha)
            throw new \Accelerator\Exception\ArgumentNullException('$captcha');

        $this->captcha = $captcha;

        parent::__construct($msg? : 'Invalid captcha');
    }

    public function validate($input) {
        return $this->captcha->isValid($input);
    }

}

?>
