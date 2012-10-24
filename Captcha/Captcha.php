<?php

namespace Accelerator\Captcha;

/**
 * Description of Captcha
 *
 * @author gg00xiv
 */
abstract class Captcha {

    const CAPTCHA_SESSION_VAR_PREFIX = 'CAPTCHA_';

    private $sessionVarName;

    /**
     *
     * @var Accelerator\Session
     */
    private $session;
    private $lastGeneratedValue;

    public function __construct() {
        $this->sessionVarName = self::CAPTCHA_SESSION_VAR_PREFIX . get_called_class();
        $this->session = \Accelerator\Application::instance()->getSession();
        if (!$this->session->isStarted()) {
            $this->session->start();
        }
        $this->lastGeneratedValue = $this->session->get($this->sessionVarName);
    }

    /**
     * Get the content of this captcha instance.
     * 
     * @return string
     */
    public function getContent() {
        $content = $this->generate($captchaValue);
        $this->session->set($this->sessionVarName, $captchaValue);

        return $content;
    }

    /**
     * Called by getHtml. Must be implemented in inherited classes.
     * 
     * @return string
     */
    protected abstract function generate(&$captchaValue);

    /**
     * Validate user input with last generated value.
     * 
     * @param string $input
     * @return boolean
     */
    public function isValid($input) {
        return $this->lastGeneratedValue == $input;
    }

}

?>
