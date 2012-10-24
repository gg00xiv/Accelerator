<?php

namespace Accelerator\Log;

/**
 * Description of DatabaseLogger
 *
 * @author gg00xiv
 */
class DatabaseLogger extends Logger {

    private $logTableName;

    public function __construct(\ArrayObject $config) {
        if (!$config->table)
            throw new \Accelerator\Exception\ConfigurationException('Invalid DatabaseLogger table name : ' . $config->table);

        $this->logTableName = $config->table;
    }

    protected function onLog($errorLevel, $message) {
        $entry = new \Accelerator\Log\LogEntry($this->logTableName, array(
                    'logDate' => date('Y-m-d H:i:s', time()),
                    'errorLevel' => $errorLevel,
                    'logMessage' => $message));

        $entry->save();
    }

}

?>
