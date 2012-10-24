<?php

namespace Accelerator\Log;

/**
 * Description of FileLogger
 *
 * @author gg00xiv
 */
class FileLogger extends Logger {

    private $logFilename;

    public function __construct(\ArrayObject $config) {
        $this->logFilename = $config->path;
    }

    protected function onLog($errorLevel, $message) {
        $fp = fopen($this->logFilename, 'a');
        fwrite($fp, date(DATE_ATOM, time()) . '[' . strtoupper($errorLevel) . '] - ' . $message . "\n");
        fclose($fp);
    }

}

?>
