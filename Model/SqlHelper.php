<?php

namespace Accelerator\Model;

/**
 * This class is a helper to create SQL statements.
 *
 * @author gg00xiv
 */
class SqlHelper {

    /**
     * Get a value indicating if the given parameter is considered as NULL for an SQL
     * statement.
     * 
     * @param mixed $value The value to check.
     * @return bool True: the $value parameter is considered as NULL
     */
    public static function isSqlNull($value) {
        return !$value && $value !== '' && $value !== 0 && $value !== '0';
    }

    /**
     * Convert a value to SQL statement value.
     * 
     * @param mixed $value Any simple (int/string/float...) value.
     * @return string The string representation of $value for a SQL statement.
     */
    public static function getSqlValue($value) {
        if (self::isSqlNull($value))
            return "NULL";
        return "'" . addslashes($value) . "'";
    }

    /**
     * Generates a WHERE SQL condition from parameter.
     * 
     * @param mixed $where The WHERE condition as array or simple string.
     * @return string A SQL WHERE condition. 
     */
    private static function getWhereSql($where) {
        $sql = '';
        if ($where) {
            if (is_string($where))
                $sql.=' WHERE ' . $where;
            else if (is_array($where) && count($where) > 0) {
                $sql.=' WHERE ';
                $whereList = array();
                foreach ($where as $field => $value) {
                    $comparer = is_string($value) && strstr($value, '%') ? 'LIKE' : (self::isSqlNull($value) ? 'IS' : '=');
                    $whereList[] = "`$field` $comparer " . self::getSqlValue($value);
                }
                $sql.=join(' AND ', $whereList);
            }
        }
        return $sql;
    }

    private static function getOrderBySql($orderBy) {
        $sql = '';
        if ($orderBy) {
            if (is_string($orderBy))
                $sql.=' ORDER BY ' . $orderBy;
            else if (is_array($orderBy) && count($orderBy) >= 1) {
                $sql.=' ORDER BY ';
                $orderByList = array();
                foreach ($orderBy as $fieldName => $direction)
                    $orderByList[] = '`' . $fieldName . '` ' . $direction;
                $sql.=join(',', $orderByList);
            }
        }
        return $sql;
    }

    private static function getGroupBySql($groupBy) {
        $sql = '';
        if ($groupBy) {
            if (is_string($groupBy))
                $sql.=' GROUP BY ' . $groupBy;
            else if (is_array($groupBy) && count($groupBy) >= 1) {
                $sql.=' GROUP BY ';
                $groupByList = array();
                foreach ($groupBy as $fieldName)
                    $groupByList[] = '`' . $fieldName . '`';
                $sql.=join(',', $groupByList);
            }
        }
        return $sql;
    }

    private static function getLimitSql($limit) {
        $sql = '';
        if ($limit) {
            if (is_string($limit) || is_numeric($limit))
                $sql.=' LIMIT ' . $limit;
            else if (is_array($limit)) {
                if (count($limit) == 1)
                    $sql.=' LIMIT ' . $limit[0];
                else if (count($limit) == 2)
                    $sql.=' LIMIT ' . $limit[0] . ',' . $limit[1];
            }
        }
        return $sql;
    }

    /**
     * Generates a SELECT SQL statement based on parameters.
     * 
     * @param string $table The database table name.
     * @param mixed $fields
     * @param mixed $where The SQL statement WHERE condition.
     * @param mixed $groupBy The SQL statement GROUP BY list.
     * @param mixed $orderBy The SQL statement ORDER BY list.
     * @param mixed $limit The SQL statement LIMIT condition.
     * @return string A SQL statement.
     */
    public static function select($table, $fields = null, $where = null, $groupBy = null, $orderBy = null, $limit = null) {
        $sql = "SELECT ";
        if (is_string($fields))
            $sql.=$fields;
        else if (is_array($fields))
            $sql.= join(',', array_map(function($fieldName) {
                                return "`$fieldName`";
                            }, $fields));
        else
            $sql.='*';

        $sql .= " FROM ";
        if (!is_array($table))
            $sql.='`' . $table . '`';
        else {
            $fromList = array();
            foreach ($table as $t)
                $fromList[] = '`' . $t . '`';
            $sql.=join(', ', $fromList);
        }

        $sql .= self::getWhereSql($where);
        $sql .= self::getGroupBySql($groupBy);
        $sql .= self::getOrderBySql($orderBy);
        $sql .= self::getLimitSql($limit);

        return $sql;
    }

    /**
     * Returns the number of rows in database table where $where condition.
     * 
     * @param string $table The database table name.
     * @param mixed The SQL statement WHERE condition.
     * @return string A SQL statement.
     */
    public static function count($table, $where = null) {
        $sql = "SELECT COUNT(*) FROM ";
        $sql .= "`" . $table . "` ";
        $sql .= self::getWhereSql($where);

        return $sql;
    }

    /**
     * Generates an UPDATE SQL statement based on parameters.
     * 
     * @param string $table The database table name.
     * @param array $set The SQL statement UPDATE SET bloc as associative array.
     * @param mixed $where The SQL statement WHERE condition.
     * @return string A SQL statement.
     */
    public static function update($table, array $set, $where = null) {
        if (!$set || count($set) == 0 || !$table)
            return '';
        $setList = array();
        foreach ($set as $field => $value)
            $setList[] = "`$field`=" . self::getSqlValue($value);
        $sql = 'UPDATE `' . $table . '` SET ' . join(',', $setList);
        $sql.=self::getWhereSql($where);
        return $sql;
    }

    /**
     * Generates an UPDATE SQL statement based on parameters.
     * 
     * @param type $table The database table name.
     * @param array $set The INSERT statement field list as associative array.
     * @return string A SQL statement.
     */
    public static function insert($table, array $set) {
        if (!$set || count($set) == 0 || !$table)
            return '';

        $fieldList = array();
        $allValueList = array();
        $singleValueList = array();
        foreach ($set as $setKey => $setValue) {

            if (is_array($setValue)) {
                $valueList = array();
                foreach ($setValue as $field => $value) {
                    if (!array_key_exists($field, $fieldList) && !is_numeric($field))
                        $fieldList[] = "`$field`";
                    $valueList[] = self::getSqlValue($value);
                }
                $allValueList[] = $valueList;
            }else {
                if (!array_key_exists($setKey, $fieldList) && !is_numeric($setKey))
                    $fieldList[] = "`$setKey`";
                $singleValueList[] = self::getSqlValue($setValue);
            }
        }
        if (count($singleValueList) > 0)
            $allValueList[] = $singleValueList;

        $fieldListSql = count($fieldList) > 0 ? ' (' . join(',', $fieldList) . ') ' : '';
        $sql = 'INSERT INTO ' . $table . $fieldListSql . ' VALUES ';
        foreach ($allValueList as $valueList) {
            $sql.=' (' . join(',', $valueList) . ')';
        }

        return $sql;
    }

    /**
     * Generates an DELETE SQL statement based on parameters.
     * 
     * @param string $table The database table name.
     * @param mixed $where The SQL statement WHERE condition.
     * @return string A SQL statement 
     */
    public static function delete($table, $where = null) {
        if (!$where)
            return 'TRUNCATE TABLE ' . $table;

        return 'DELETE FROM `' . $table . '` ' . self::getWhereSql($where);
    }

}

?>
