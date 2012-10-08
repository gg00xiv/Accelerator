<?php

namespace Accelerator\Model;

use Accelerator\Config;
/**
 * Create a generic database connection given a Config object.
 *
 * @author gg00xiv
 */
class GenericDatabase {

    protected $connection;

    public function __construct(Config $config) {

        $adapterClassName = '\\Accelerator\\Model\\Driver\\' . $config->driver . '\\' . $config->driver . 'Connection';

        $this->connection = new $adapterClassName($config);
        
    }

    /**
     * Get an instance of DbConnection implementation.
     * 
     * @return \Accelerator\Model\Driver\DbConnection
     */
    public function getConnection() {
        return $this->connection;
    }

}

?>
