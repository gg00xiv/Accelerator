<?php

namespace Accelerator\Model;

/**
 * DbEntity is a class your model objects should inherits to implement
 * connectivity to Application database.
 * You can also use this class in state to create direct connectivity to
 * database table.
 *
 * @author gg00xiv
 */
class DbEntity {
    /**
     * Refactoring based on public vars in the derived class.
     */

    const LOAD_MODE_FIELDS = 0;

    /**
     * Refactoring based on getField/setField methods in the derived class.
     */
    const LOAD_MODE_METHODS = 1;

    /**
     * No refactoring, explicit map and parameters are given to the object from config. 
     */
    const LOAD_MODE_CONFIG = 2;

    /**
     * Array of primary key database columns
     * 
     * @var array
     */
    protected $primaryKeyColumns;

    /**
     * The first primary key column of $primaryKeyColumns set
     * 
     * @var string
     */
    private $primaryKeyColumn;

    /**
     * Primary key must be unique to match with autoIncrementPk=true.
     * True => auto update primary key value on insert
     * 
     * @var bool
     */
    protected $autoIncrementPk = true;

    /**
     * The database table name.
     * 
     * @var string
     */
    protected $table;

    /**
     * The underlying DbConnection.
     * 
     * @var \Accelerator\Model\Driver\DbConnection
     */
    protected $connection;

    /**
     * Refactoring mode to map this DbEntity to database table.
     * 
     * @var int
     */
    protected $loadMode = self::LOAD_MODE_FIELDS;

    /**
     * Getters map.
     * 
     * @var array
     */
    protected $get_map = array();

    /**
     * Setters map.
     * 
     * @var array
     */
    protected $set_map = array();
    protected $encoding = 'utf-8';
    private static $configCache;

    /**
     * Create a generic DbEntity based on a table name.
     * 
     * @param array $initVars Initialize instance vars.
     * @throws \Accelerator\AcceleratorException
     */
    public function __construct(array $initVars = null) {

        if (!self::$configCache)
            self::$configCache = new \Accelerator\Cache\MemoryCache ();

        $calledClassName = get_called_class();
        if (($selfConfig = self::$configCache->get($calledClassName)) == null) {
            // set connection and validate it
            $this->connection = \Accelerator\Application::instance()->getDbConnection();
            if (!$this->connection)
                throw new Exception\ModelException('No/Invalid connection specified.');

            switch ($this->loadMode) {
                case self::LOAD_MODE_CONFIG:
                    $config = \Accelerator\Application::instance()->getEntityConfig($this);
                    $this->initFromConfig($config);
                    break;

                case self::LOAD_MODE_FIELDS:
                    $this->initFromVars();
                    break;

                case self::LOAD_MODE_METHODS:
                    $this->initFromMethods();
                    break;
            }

            $this->checkIntegrity();

            self::$configCache->put($calledClassName, array(
                'connection' => $this->connection,
                'loadMode' => $this->loadMode,
                'autoIncrementPk' => $this->autoIncrementPk,
                'primaryKeyColumns' => $this->primaryKeyColumns,
                'primaryKeyColumn' => $this->primaryKeyColumn,
                'set_map' => $this->set_map,
                'get_map' => $this->get_map,
                'table' => $this->table,
                'encoding' => $this->encoding,
            ));
        } else {
            foreach ($selfConfig as $property => $value) {
                $this->$property = $value;
            }
        }

        if ($initVars) {
            foreach ($initVars as $var => $value) {
                $this->$var = $value;
            }
        }
    }

    /**
     * Returns the field value based on its name. This method is used
     * internally to access field values whereas accessor types are not
     * statically known
     * 
     * @param string $fieldName
     * @return mixed
     */
    public function getFieldValue($fieldName) {
        if (!array_key_exists($fieldName, $this->get_map))
            throw new Exception\ModelException('Invalid field name : ' . $fieldName);
        $accessor = $this->get_map[$fieldName];
        $fieldValue = method_exists($this, $accessor) ? $this->$accessor() : $this->$accessor;

        return $fieldValue;
    }

    /**
     * Set the field value based on its name. This method is used
     * internally to access field values whereas accessor types are not
     * statically known
     * 
     * @param string $fieldName
     * @param mixed $fieldValue
     */
    public function setFieldValue($fieldName, $fieldValue) {
        $accessor = !array_key_exists($fieldName, $this->set_map) ?
                $fieldName :
                $this->set_map[$fieldName];

        if (method_exists($this, $accessor))
            $this->$accessor($fieldValue);
        else
            $this->$accessor = $fieldValue;
    }

    /**
     * Returns the underlying database table name associated to this DbEntity object.
     * 
     * @return string
     */
    public function getTable() {
        return $this->table;
    }

    /**
     * Returns the underlying connection associated to this DbEntity object.
     * 
     * @return \Accelerator\Model\Driver\DbConnection
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Returns SQL column names used for selecting rows of this DbEntity.
     * 
     * @return array Array of string.
     */
    public function getSelectFields() {
        return array_keys($this->get_map);
    }

    /**
     * Get a filter map of SQL columns with their SQL value when using this DbEntity
     * as a filter.
     * 
     * @param type $ignoreNullFields Ignores SQL-NULL fields for selecting entities (Default=true).
     * @return array Array of SQL columns => SQL values
     */
    public function getSqlFilterMap($ignoreNullFields = true) {
        $map = array();
        foreach ($this->getColumnValues() as $column => $value) {
            if (!$ignoreNullFields || !SqlHelper::isSqlNull($value))
                $map[$column] = $value;
        }
        return $map;
    }

    /**
     * Returns the list of fields with their value
     * 
     * @return array
     */
    public function getColumnValues() {
        $columns = array();
        foreach (array_keys($this->get_map) as $column) {
            $columns[$column] = $this->getFieldValue($column);
        }
        return $columns;
    }

    /**
     * Save entity to database, internal use of insert or update depending on entity status
     */
    public function save() {
        if ($this->hasPrimaryKey()) {
            foreach ($this->primaryKeyColumns as $pk) {
                $pk_value = $this->getfieldValue($pk);
                if (!$pk_value) { // 0, null, empty string
                    $this->insert();
                    return;
                }
            }
        }
        $this->update();
    }

    /**
     * Delete entity from database.
     */
    public function delete() {
        $sql = SqlHelper::delete($this->table, $this->getPrimaryKeyValues());

        $this->_isDeleted = true;
        return $this->connection->executeNonQuery($sql);
    }

    /**
     * Checks whether the current entity has been deleted from database.
     * 
     * @return boolean
     */
    public function isDeleted() {
        return $this->_isDeleted;
    }

    /**
     * Select entities based on $this model.
     * 
     * @return Accelerator\Model\DbEntityCollection
     */
    public function select() {
        return EntityManager::select($this);
    }

    /**
     * Get a JSON representation of the DbEntity instance.
     * 
     * @return string JSON string. 
     */
    public function __toString() {
        $ret = array();
        foreach ($this->getColumnValues() as $column => $value) {
            $ret[] = $column . ':' . SqlHelper::getSqlValue($value);
        }
        return '{' . join(', ', $ret) . '}';
    }

    private function checkIntegrity() {
        if (!$this->table)
            throw new Exception\ModelException('No/Invalid table name specified.');

        if ($this->hasPrimaryKey()) {
            // check if only one primary key is defined if auto_increment_pk parameter is set to true
            if ($this->autoIncrementPk && count($this->primaryKeyColumns) != 1)
                throw new Exception\ModelException('You must declare one and only one primary key column when auto_increment_pk parameter is set to true.');
            // verify if primary keys are available in entity
            foreach ($this->primaryKeyColumns as $pk)
                if (!array_key_exists($pk, $this->get_map) || !array_key_exists($pk, $this->set_map))
                    throw new Exception\ModelException('The ' . $pk . ' primary key get and/or set accessors were not found in current entity.');
        }
    }

    private function initPrimaryKeyColumns() {
        if (!is_array($this->primaryKeyColumns))
            $this->primaryKeyColumns = array($this->primaryKeyColumns);
        $this->primaryKeyColumn = $this->primaryKeyColumns[0];
    }

    private function initFromConfig($config) {
        if (is_string($config->table))
            $this->table = $config->table;

        if ($config->primary_key_columns)
            $this->primaryKeyColumns = $config->primary_key_columns;
        $this->initPrimaryKeyColumns();

        if (is_bool($config->auto_increment_pk))
            $this->autoIncrementPk = $config->auto_increment_pk;

        if ($config->map instanceof \Traversable) {
            foreach ($config->map as $column => $accessor) {
                if ($accessor instanceof \ArrayObject) {
                    if (isset($accessor->get))
                        $this->get_map[$column] = $accessor->get;
                    if (isset($accessor->set))
                        $this->set_map[$column] = $accessor->set;
                } else {
                    $this->get_map[$column] = $accessor;
                    $this->set_map[$column] = $accessor;
                }
            }
        }
    }

    private function initFromVars() {
        $this->initPrimaryKeyColumns();

        $rClass = new \ReflectionClass($this);

        $vars = $rClass->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($vars as $var) {
            $fieldName = $var->getName();
            $this->get_map[$fieldName] = $fieldName;
            $this->set_map[$fieldName] = $fieldName;
        }
    }

    private function initFromMethods() {
        $this->initPrimaryKeyColumns();

        $rClass = new \ReflectionClass($this);

        $methods = $rClass->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (strtolower(substr($methodName, 0, 3)) == 'get') {
                $fieldName = lcfirst(substr($methodName, 3));
                $this->get_map[$fieldName] = $methodName;
            } else if (strtolower(substr($methodName, 0, 3)) == 'set') {
                $fieldName = lcfirst(substr($methodName, 3));
                if (!$this->autoIncrementPk || !in_array($fieldName, $this->primaryKeyColumns))
                    $this->set_map[$fieldName] = $methodName;
            }
        }
    }

    private function getPrimaryKeyValues() {
        if (!$this->hasPrimaryKey())
            return array();
        $pks = array();
        foreach ($this->primaryKeyColumns as $pk)
            $pks[$pk] = $this->getFieldValue($pk);
        return $pks;
    }

    private function hasPrimaryKey() {
        return is_array($this->primaryKeyColumns) && count($this->primaryKeyColumns) > 0;
    }

    private function insert() {
        $sql = SqlHelper::insert($this->table, $this->getColumnValues());

        $ret = $this->connection->executeNonQuery($sql, $this->autoIncrementPk);
        if ($this->autoIncrementPk) {
            $this->setFieldValue($this->primaryKeyColumn, $ret);
        }
    }

    private function update() {
        $sql = SqlHelper::update($this->table, $this->getColumnValues(), $this->getPrimaryKeyValues());

        $this->connection->executeNonQuery($sql);
    }

}

?>
