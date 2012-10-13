<?php

namespace Accelerator\Model\Driver\MySql;

use Accelerator\Model\Driver\DbConnection;
use Accelerator\AcceleratorException;

/**
 * MySql connection class.
 *
 * @author gg00xiv
 */
class MySqlConnection extends DbConnection {

    protected $dbLink;

    public function __construct($config) {
        parent::__construct($config);
    }

    /**
     * Connect to MySql server and select database.
     * 
     * @throws AcceleratorException 
     */
    public function open() {
        if (($this->dbLink = mysql_connect($this->host, $this->username, $this->password)) !== false)
            mysql_select_db($this->dbname, $this->dbLink);
        else
            throw new AcceleratorException('Cannot connect database.');
    }

    /**
     * Explicitly close MySql server connection. 
     */
    public function close() {
        mysql_close($this->dbLink);
    }

    /**
     * Execute a SQL query on opened connection and returns a row set.
     * 
     * @param string $sql SQL statement.
     * @return array Row set array. 
     */
    public function executeQuery($sql) {
        if (in_array(strtolower(substr($sql, 0, 7)), array('insert ', 'update ', 'delete ')))
            throw new AcceleratorException('executeNonQuery method cannot execute SELECT SQL statement.');

        $req = mysql_query($sql, $this->dbLink);
        if (!$req)
            throw new AcceleratorException("Invalid request : \n".$sql."\n".mysql_error());
        $rowset = array();
        while ($row = mysql_fetch_array($req))
            $rowset[] = $row;
        return $rowset;
    }

    /**
     * Execute a SQL (INSERT/UPDATE or DELETE) non-query on opened connection.
     * 
     * @param type $sql SQL statement.
     * @param type $mustReturnAutoId
     * @return mixed Nothing or auto generated id for INSERT SQL statement.
     * @throws AcceleratorException If called with SELECT SQL statement.
     */
    public function executeNonQuery($sql, $mustReturnAutoId = false) {
        if (strtolower(substr($sql, 0, 7)) == 'select ')
            throw new AcceleratorException('executeNonQuery method cannot execute SELECT SQL statement.');

        $req = mysql_query($sql, $this->dbLink);
        if (!$req)
            throw new AcceleratorException('Invalid request : '.mysql_error());
        if ($mustReturnAutoId)
            return mysql_insert_id($this->dbLink);
    }

}

?>