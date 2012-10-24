<?php

namespace Accelerator\Log;

/**
 * Description of Logger
 *
 * @author gg00xiv
 */
abstract class Logger {

    const ERROR_LEVEL_ERROR = 'error';
    const ERROR_LEVEL_WARN = 'warn';
    const ERROR_LEVEL_INFO = 'info';
    const ERROR_LEVEL_DEBUG = 'debug';

    public function log($errorLevel, $message) {
        if ($errorLevel != self::ERROR_LEVEL_DEBUG && $errorLevel
                != self::ERROR_LEVEL_INFO && $errorLevel != self::ERROR_LEVEL_WARN && $errorLevel != self::ERROR_LEVEL_ERROR)
            throw new Exception\LogException();

        return $this->onLog($errorLevel, $message);
    }

    public function error($message) {
        $this->log(self::ERROR_LEVEL_ERROR, $message);
    }

    public function warn($message) {
        $this->log(self::ERROR_LEVEL_WARN, $message);
    }

    public function info($message) {
        $this->log(self::ERROR_LEVEL_INFO, $message);
    }

    public function debug($message) {
        $this->log(self::ERROR_LEVEL_DEBUG, $message);
    }

    protected abstract function onLog($errorLevel, $message);
}

?>
