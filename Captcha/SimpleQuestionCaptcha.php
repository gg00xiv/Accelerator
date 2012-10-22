<?php

namespace Accelerator\Captcha;

/**
 * Description of SimpleQuestionCaptcha
 *
 * @author gg00xiv
 */
class SimpleQuestionCaptcha extends Captcha {
    
    /*public function __construct(){
        parent::__construct();
    }*/

    protected function generate(&$captchaValue) {
        $a = rand(0, 10);
        $b = rand(0, 10);
        
        switch (rand() % 3) {
            case 0:
                $captchaValue = $a + $b;
                return 'How do ' . $a . ' and ' . $b . ' ?';
            case 1:
                $captchaValue = $a * $b;
                return 'How do ' . $a . ' multiplied by ' . $b . ' ?';
            case 2:
                $captchaValue = $a - $b;
                return 'How do ' . $a . ' minus ' . $b . ' ?';
        }
    }

}

?>
