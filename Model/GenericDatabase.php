<?php

namespace Accelerator\Model;

/**
 * Description of GenericDatabase
 *
 * @author gg00xiv
 */
class GenericDatabase {

    protected $connection;

    public function __construct($config) {

        $adapterClassName = '\\EasyMvc\\Model\\Driver\\' . $config->driver . '\\' . $config->driver . 'Connection';

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
