<?php

namespace Accelerator\Model\Exception;

/**
 * Description of ModelException
 *
 * @author gg00xiv
 */
class ModelException extends \Exception {

    private $sql;

    public function __construct($message, $sql = null) {
        parent::__construct($message);
        $this->sql = $sql;
    }

    public function getSql() {
        return $this->sql;
    }

}

?>
