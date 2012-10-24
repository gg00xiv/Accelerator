<?php

namespace Accelerator;

/**
 * Description of Session
 *
 * @author gg00xiv
 */
class Session {

    /**
     * Gets the session identifier.
     * 
     * @return string
     */
    public function getId() {
        return session_id();
    }

    /**
     * Checks whether the session is started or not.
     * 
     * @return boolean
     */
    public function isStarted() {
        return $this->getId() ? true : false;
    }

    public function start() {
        return session_start();
    }

    public function destroy() {
        return session_destroy();
    }

    public function __get($name) {
        return $_SESSION[$name];
    }

    public function __set($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function get($name) {
        return $this->$name;
    }

    public function set($name, $value) {
        $this->$name = $value;
    }

}

?>
