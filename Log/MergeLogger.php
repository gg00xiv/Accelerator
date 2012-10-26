<?php

namespace Accelerator\Log;

/**
 * Description of MergeLogger
 *
 * @author gg00xiv
 */
class MergeLogger extends Logger {

    private $loggers;

    public function __construct(array $names) {
        $this->loggers = array();
        foreach ($names as $name) {
            $this->loggers[] = LogManager::getLogger($name);
        }
    }

    protected function onLog($errorLevel, $message) {
        foreach ($this->loggers as $logger)
            $logger->log($errorLevel, $message);
    }

}

?>
