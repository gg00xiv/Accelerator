<?php

namespace Accelerator\Log;

/**
 * Description of FileLogger
 *
 * @author gg00xiv
 */
class FileLogger extends Logger {

    private $logFilename;
    private $revertlog;

    public function __construct(\ArrayObject $config) {
        $this->logFilename = $config->path;
        $this->revertlog = $config->revertlog;
    }

    protected function onLog($errorLevel, $message) {
        $logMessage = date(DATE_ATOM, time()) . '[' . strtoupper($errorLevel) . '] - ' . $message . "\n";

        if ($this->revertlog) {
            file_put_contents($this->logFilename, $logMessage . file_get_contents($this->logFilename));
        } else {
            $fp = fopen($this->logFilename, 'a');
            fwrite($fp, $logMessage);
            fclose($fp);
        }
    }

}

?>
