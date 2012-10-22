<?php

namespace Accelerator\Captcha;

/**
 * Description of Captcha
 *
 * @author gg00xiv
 */
abstract class Captcha {

    const CAPTCHA_SESSION_VAR = 'CAPTCHA_SESSION_VAR';

    /**
     *
     * @var Accelerator\Session
     */
    private $session;
    private $lastGeneratedValue;

    public function __construct() {
        $this->session = \Accelerator\Application::instance()->getSession();
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $sessionVar = self::CAPTCHA_SESSION_VAR;
        $this->lastGeneratedValue = $this->session->$sessionVar;
    }

    /**
     * Get the HTML content of this captcha instance.
     * 
     * @return string
     */
    public function getHtml() {
        $html = $this->generate($captchaValue);
        $sessionVar = self::CAPTCHA_SESSION_VAR;
        $this->session->$sessionVar = $captchaValue;

        return $html;
    }

    /**
     * Called by getHtml. Must be implemented in inherited classes.
     * 
     * @return string
     */
    protected abstract function generate(&$captchaValue);

    public function isValid($input) {
        return $this->lastGeneratedValue == $input;
    }

}

?>
