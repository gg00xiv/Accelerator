<?php

namespace EasyMvc\Model;

use EasyMvc\Model\DbEntity;
use EasyMvc\Model\SqlHelper;
use EasyMvc\EasyMvcException;

/**
 * Get and Remove entities from database based on templated DbEntity objects.
 *
 * @author gg00xiv
 */
abstract class EntityManager {

    /**
     * Returns a set of $filter type objects using the $filter template.
     * 
     * @param \EasyMvc\Model\DbEntity $filter
     * @param bool $ignoreNullFields Ignores SQL-NULL fields for selecting entities (Default=true).
     * @return array;
     */
    public static function select(DbEntity $filter, $ignoreNullFields = true) {
        $selectFields = array();
        $where = array();
        foreach ($filter->getColumnValues() as $column => $value) {
            $selectFields[] = $column;
            if (!$ignoreNullFields || !SqlHelper::isSqlNull($value))
                $where[$column] = $value;
        }
        $sql = SqlHelper::select($filter->getTable(), $selectFields, $where);

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

    public static function selectSingle(DbEntity $filter, $ignoreNullFields = true) {
        $entities = self::select($filter, $ignoreNullFields);
        if (count($entities) > 1)
            throw new AcceleratorException('More than one entity returned.');

        return count($entities) == 1 ? $entities[0] : null;
    }

    /**
     * Returns a set of $filter type objects using the $filter template.
     * 
     * @param \EasyMvc\Model\DbEntity $filter
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
