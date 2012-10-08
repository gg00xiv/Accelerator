<?php

namespace EasyMvc\Model\Driver;

/**
 * Description of DbConnection
 *
 * @author gg00xiv
 */
abstract class DbConnection {

    protected $host;
    protected $dbname;
    protected $username;
    protected $password;
    
    public function __construct($config) {

        $this->host = $config->host;
        $this->dbname = $config->dbname;
        $this->username = $config->username;
        $this->password = $config->password;
    }
    
    public function getHost(){
        return $this->host;
    }
    
    public function getDbName(){
        return $this->dbname;
    }
    
    public function getUsername(){
        return $this->username;
    }
    
    public function getPassword(){
        return $this->password;
    }

    public abstract function open();

    public abstract function close();

    public abstract function executeQuery($sql);

    public abstract function executeNonQuery($sql, $mustReturnAutoId = false);
}

?>
