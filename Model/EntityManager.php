<?php

namespace Accelerator\Model;

use Accelerator\Model\DbEntity;
use Accelerator\Model\SqlHelper;
use Accelerator\AcceleratorException;

/**
 * Get and Remove entities from database based on templated DbEntity objects.
 *
 * @author gg00xiv
 */
abstract class EntityManager {

    /**
     * Returns a set of $filter type entities based on $filter DbEntity template.
     * 
     * @param \Accelerator\Model\DbEntity $filter DbEntity template instance for filtering.
     * @param mixed $orderBy The SQL statement ORDER BY list.
     * @param mixed $limit The SQL statement LIMIT condition.
     * @param bool $ignoreNullFields Ignores SQL-NULL fields for selecting entities (Default=true).
     * @return array;
     */
    public static function select(DbEntity $filter, $orderBy = null, $limit = null, $ignoreNullFields = true) {
        $selectFields = array();
        $where = array();
        foreach ($filter->getColumnValues() as $column => $value) {
            $selectFields[] = $column;
            if (!$ignoreNullFields || !SqlHelper::isSqlNull($value))
                $where[$column] = $value;
        }
        $sql = SqlHelper::select($filter->getTable(), $selectFields, $where, $orderBy, $limit);
        $rowset = $filter->getConnection()->executeQuery($sql);

        $entities = array();
        foreach ($rowset as $row) {
            $entity = clone $filter;
            foreach ($row as $column => $value) {
                if (is_numeric($column))
                    continue;
                $entity->setFieldValue($column, $value);
            }
            $entities[] = $entity;
        }

        return $entities;
    }
    
   /**
    * Returns one and only one entity based on $filter DbEntity template.
    * 
    * @param \Accelerator\Model\DbEntity $filter DbEntity template instance for filtering.
    * @param type $ignoreNullFields Ignores SQL-NULL fields for selecting entities (Default=true).
    * @return \Accelerator\Model\DbEntity DbEntity instance.
    * @throws \Accelerator\AcceleratorException If more than one entity returned.
    */
    public static function selectSingle(DbEntity $filter, $ignoreNullFields = true) {
        $entities = self::select($filter, null, null, $ignoreNullFields);
        $countEntities = count($entities);
        if ($countEntities > 1)
            throw new AcceleratorException('More than one entity returned : ' . $countEntities . ' [' . $filter . ']');

        return $countEntities == 1 ? $entities[0] : null;
    }
    
    /**
     * Returns the number of rows based on $filter DbEntity template. 
     * 
     * @param \Accelerator\Model\DbEntity $filter DbEntity template instance for filtering.
     * @param type $ignoreNullFields Ignores SQL-NULL fields for selecting entities (Default=true).
     * @return number Number of rows.
     */
    public static function count(DbEntity $filter, $ignoreNullFields = true){
        $where = array();
        foreach ($filter->getColumnValues() as $column => $value) {
            if (!$ignoreNullFields || !SqlHelper::isSqlNull($value))
                $where[$column] = $value;
        }
        $sql = SqlHelper::count($filter->getTable(), $where);
        $rowset = $filter->getConnection()->executeQuery($sql);

        return $rowset[0][0];
    }

    /**
     * Returns a set of $filter type objects using the $filter template.
     * 
     * @param \Accelerator\Model\DbEntity $filter
     * @param bool $ignoreNullFields Ignores SQL-NULL fields for deleting entities (Default=true).
     */
    public static function delete(DbEntity $filter, $ignoreNullFields = true) {
        $where = array();
        foreach ($filter->getColumnValues() as $column => $value) {
            if (!$ignoreNullFields || !SqlHelper::isSqlNull($value))
                $where[$column] = $value;
        }
        $sql = SqlHelper::delete($filter->getTable(), $where);

        $filter->getConnection()->executeNonQuery($sql);
    }

}

?>
