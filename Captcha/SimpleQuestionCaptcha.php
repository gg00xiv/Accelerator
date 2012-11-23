<?php

namespace Accelerator\Captcha;

/**
 * Description of SimpleQuestionCaptcha
 *
 * @author gg00xiv
 */
class SimpleQuestionCaptcha extends Captcha {

    private $formats;

    public function __construct($formats = null) {
        parent::__construct();
        if (!$formats) {
            $this->formats = array(
                'How do %d and %d ?',
                'How do %d multiplied by %d ?',
                'How do %d minus %d ?'
            );
        }else{
            $this->formats=$formats;
        }
    }

    protected function generate(&$captchaValue) {
        $a = rand(0, 10);
        $b = rand(0, 10);

        $rand = rand() % 3;
        switch ($rand) {
            case 0:
                $captchaValue = $a + $b;
                break;
            case 1:
                $captchaValue = $a * $b;
                break;
            case 2:
                $captchaValue = $a - $b;
                break;
        }
        
        return sprintf($this->formats[$rand], $a, $b);
    }

}

?>
