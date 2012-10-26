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
        $sql = SqlHelper::select($filter->getTable(), $filter->getSelectFields(), $filter->getSqlFilterMap($ignoreNullFields), null, $orderBy, $limit);
        return self::selectWhereSql($filter, $sql);
    }

    /**
     * Returns a set of $filter type entities based on $filter DbEntity template.
     * 
     * @param \Accelerator\Model\DbEntity $template The DbEntity to use as template for entities retrieved.
     * @param string $sql SQL full SELECT request.
     * @return \Accelerator\Model\DbEntityCollection
     */
    public static function selectWhereSql(DbEntity $template, $sql) {
        $rowset = $template->getConnection()->executeQuery($sql);

        $entities = new DbEntityCollection();
        foreach ($rowset as $row) {
            $entity = clone $template;
            foreach ($row as $column => $value) {
                if (is_numeric($column))
                    continue;
                $entity->setFieldValue($column, $value);
            }
            $entities->append($entity);
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
            throw new \Accelerator\Exception\AcceleratorException('More than one entity returned : ' . $countEntities . ' [' . $filter . ']');

        return $countEntities == 1 ? $entities[0] : null;
    }

    /**
     * Returns the number of rows based on $filter DbEntity template. 
     * 
     * @param \Accelerator\Model\DbEntity $filter DbEntity template instance for filtering.
     * @param type $ignoreNullFields Ignores SQL-NULL fields for selecting entities (Default=true).
     * @return int Number of rows.
     */
    public static function count(DbEntity $filter, $ignoreNullFields = true) {
        $where = array();
        foreach ($filter->getColumnValues() as $column => $value) {
            if (!$ignoreNullFields || !SqlHelper::isSqlNull($value))
                $where[$column] = $value;
        }

        return self::countWhereSql($filter, $where);
    }

    /**
     * Returns the number of rows of $filter DbEntity based on SQL WHERE condition.
     * 
     * @param \Accelerator\Model\DbEntity $template DbEntity template.
     * @param type $where SQL WHERE condition.
     * @return int Number of rows in database $template DbEntity table. 
     */
    public static function countWhereSql(DbEntity $template, $where = null) {
        $sql = SqlHelper::count($template->getTable(), $where);
        $rowset = $template->getConnection()->executeQuery($sql);

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
