<?php

namespace Accelerator\Log;

/**
 * Description of LogEntry
 *
 * @author gg00xiv
 */
class LogEntry extends \Accelerator\Model\DbEntity {

    public $logId;
    public $logDate;
    public $errorLevel;
    public $logMessage;
    protected $loadMode = parent::LOAD_MODE_FIELDS;
    protected $primaryKeyColumns = 'logId';

    public function __construct($logTableName, array $initVars = null) {
        $this->table = $logTableName;
        parent::__construct($initVars);
    }

}

?>
