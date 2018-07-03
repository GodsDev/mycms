<?php

namespace GodsDev\MyCMS;

use GodsDev\Backyard\BackyardMysqli;

/**
 * class with logging specific to this application
 * i.e. log changes of database
 */
class LogMysqli extends BackyardMysqli
{

    use \Nette\SmartObject;

    protected $KEYWORDS = array(
        'ACCESSIBLE', 'ADD', 'ALL', 'ALTER', 'ANALYZE', 'AND', 'AS', 'ASC', 'ASENSITIVE', 
        'BEFORE', 'BETWEEN', 'BIGINT', 'BINARY', 'BLOB', 'BOTH', 'BY', 'CALL', 'CASCADE',
        'CASE', 'CHANGE', 'CHAR', 'CHARACTER', 'CHECK', 'COLLATE', 'COLUMN', 'CONDITION', 
        'CONSTRAINT', 'CONTINUE', 'CONVERT', 'CREATE', 'CROSS', 'CURRENT_DATE', 
        'CURRENT_TIME', 'CURRENT_TIMESTAMP', 'CURRENT_USER', 'CURSOR', 'DATABASE', 
        'DATABASES', 'DAY_HOUR', 'DAY_MICROSECOND', 'DAY_MINUTE', 'DAY_SECOND', 
        'DEC', 'DECIMAL', 'DECLARE', 'DEFAULT', 'DELAYED', 'DELETE', 'DESC', 'DESCRIBE', 
        'DETERMINISTIC', 'DISTINCT', 'DISTINCTROW', 'DIV', 'DOUBLE', 'DROP', 'DUAL',
        'EACH', 'ELSE', 'ELSEIF', 'ENCLOSED', 'ESCAPED', 'EXISTS', 'EXIT', 'EXPLAIN', 
        'FALSE', 'FETCH', 'FLOAT', 'FLOAT4', 'FLOAT8', 'FOR', 'FORCE', 'FOREIGN',
        'FROM', 'FULLTEXT', 'GRANT', 'GROUP', 'HAVING', 'HIGH_PRIORITY', 'HOUR_MICROSECOND', 
        'HOUR_MINUTE', 'HOUR_SECOND', 'IF', 'IGNORE', 'IN', 'INDEX', 'INFILE', 'INNER', 
        'INOUT', 'INSENSITIVE', 'INSERT', 'INT', 'INT1', 'INT2', 'INT3', 'INT4', 
        'INT8', 'INTEGER', 'INTERVAL', 'INTO', 'IS', 'ITERATE', 'JOIN', 'KEY', 'KEYS',
        'KILL', 'LEADING', 'LEAVE', 'LEFT', 'LIKE', 'LIMIT', 'LINEAR', 'LINES', 
        'LOAD', 'LOCALTIME', 'LOCALTIMESTAMP', 'LOCK', 'LONG', 'LONGBLOB', 'LONGTEXT',
        'LOOP', 'LOW_PRIORITY', 'MASTER_SSL_VERIFY_SERVER_CERT', 'MATCH', 'MEDIUMBLOB', 
        'MEDIUMINT', 'MEDIUMTEXT', 'MIDDLEINT', 'MINUTE_MICROSECOND', 'MINUTE_SECOND', 
        'MOD', 'MODIFIES', 'NATURAL', 'NOT', 'NO_WRITE_TO_BINLOG', 'NULL', 'NUMERIC', 
        'ON', 'OPTIMIZE', 'OPTION', 'OPTIONALLY', 'OR', 'ORDER', 'OUT', 'OUTER', 
        'OUTFILE', 'PRECISION', 'PRIMARY', 'PROCEDURE', 'PURGE', 'RANGE', 'READ', 
        'READS', 'READ_ONLY', 'READ_WRITE', 'REAL', 'REFERENCES', 'REGEXP', 'RELEASE',
        'RENAME', 'REPEAT', 'REPLACE', 'REQUIRE', 'RESTRICT', 'RETURN', 'REVOKE', 
        'RIGHT', 'RLIKE', 'SCHEMA', 'SCHEMAS', 'SECOND_MICROSECOND', 'SELECT', 'SENSITIVE', 
        'SEPARATOR', 'SET', 'SHOW', 'SMALLINT', 'SPATIAL', 'SPECIFIC', 'SQL', 'SQLEXCEPTION', 
        'SQLSTATE', 'SQLWARNING', 'SQL_BIG_RESULT', 'SQL_CALC_FOUND_ROWS', 'SQL_SMALL_RESULT',
        'SSL', 'STARTING', 'STRAIGHT_JOIN', 'TABLE', 'TERMINATED', 'THEN', 'TINYBLOB', 
        'TINYINT', 'TINYTEXT', 'TO', 'TRAILING', 'TRIGGER', 'TRUE', 'UNDO', 'UNION',
        'UNIQUE', 'UNLOCK', 'UNSIGNED', 'UPDATE', 'USAGE', 'USE', 'USING', 'UTC_DATE', 
        'UTC_TIME', 'UTC_TIMESTAMP', 'VALUES', 'VARBINARY', 'VARCHAR', 'VARCHARACTER', 
        'VARYING', 'WHEN', 'WHERE', 'WHILE', 'WITH', 'WRITE', 'XOR', 'YEAR_MONTH', 
        'ZEROFILL'
    );

    /** @var array */
    protected $sqlStatementsArray = array();

    /**
     * Logs SQL statement not starting with SELECT or SET
     *
     * @param string $sql SQL to execute
     * @param bool $ERROR_LOG_OUTPUT
     * @return \mysqli_result Object|false
     * @throws DBQueryException
     */
    public function query($sql, $ERROR_LOG_OUTPUT = true)
    {
        if (!preg_match('/^SELECT |^SET |^SHOW /i', $sql)) {
            //mb_eregi_replace does not destroy e.g. character Š
            error_log(trim(mb_eregi_replace('/\s+/', ' ', $sql)) . '; -- [' . date("d-M-Y H:i:s") . ']' . (isset($_SESSION['user']) ? " by ({$_SESSION['user']})" : '') . PHP_EOL, 3, 'log/sql' . date("Y-m-d") . '.log.sql');
        }
        $this->sqlStatementsArray[] = $sql;
        return parent::query($sql, $ERROR_LOG_OUTPUT);
    }

    public function getStatementsArray()
    {
        return $this->sqlStatementsArray;
    }

    /**
     * Escape a string constant - specific to MySQL/MariaDb and current collation
     *
     * @param string $string to escape
     * @return string
     */
    public function escapeSQL($string)
    {
        return $this->real_escape_string($string);
    }

    /**
     * Escape a database identifier (table/column name, etc.) - specific to MySQL/MariaDb
     *
     * @param string $string to escape
     * @return string escaped identifier
     */
    public function escapeDbIdentifier($string)
    {
        $string = str_replace('`', '``', $string);
        if (!preg_match('/[^a-z0-9_]+/i', $string) || in_array($string, $this->KEYWORDS)) {
            $string = "`$string`";
        }
        return $string;
    }

    /**
     * Decode options in 'set' and 'enum' columns - specific to MySQL/MariaDb
     *
     * @param string $list list of options (e.g. "enum('single','married','divorced')" or just "'single','married','divorced'")
     * @return array 
     */
    public function decodeChoiceOptions($list)
    { //e.g. value: '0','a''b','c"d','e\\f','','g`h' should be ['0', "a'b", 'c"d', 'e\f', '', 'g`h'
        if (($result = substr($list, 0, 5) == 'enum(') || substr($list, 0, 4) == 'set(') {
            $list = substr($list, $result ? 5 : 4, -1);
        }
        $list = substr($list, 0, 1) == "'" ? $list : '';
        preg_match_all("~'((''|[^'])*',)*~i", "$list,", $result);
        $result = isset($result[1]) ? $result[1] : array();
        foreach ($result as &$value) {
            $value = strtr(substr($value, 0, -2), array("''" => "'", "\\\\" => "\\"));
        }
        return $result;
    }

    /**
     * Decode options in 'set' columns - specific to MySQL/MariaDb
     *
     * @param string $list list of options (e.g. ""
     * @return array 
     */
    public function decodeSetOptions($list)
    {
        if (substr($list, 0, 4) == 'set(') {
            $list = substr($list, 4, -1);
        }
        $result = explode(',', $list);
        foreach ($result as &$value) {
            $value = strtr(substr($value, 1, -1), array("''" => "'", "\\\\" => "\\"));
        }
        return $result;
    }

    /**
     * Check wheter given interval matches the format for expression used after MySQL's keyword 'INTERVAL' - specific to MySQL/MariaDb
     *
     * @param string $interval
     * @result int 1=yes, 0=no, false=error
     */
    public function checkIntervalFormat($interval)
    {
        $int = '\s*\-?\d+\s*';
        return preg_match("~^\s*((?:\-?\s*(?:\d*\.?\d+|\d+\.?\d*)(?:e[\+\-]?\d+)?)\s+(MICROSECOND|SECOND|MINUTE|HOUR|DAY|WEEK|MONTH|QUARTER|YEAR)"
            . "|\'$int.$int\'\s*(SECOND|MINUTE|HOUR|DAY)_MICROSECOND"
            . "|\'$int:$int\'\s*(MINUTE_SECOND|HOUR_SECOND)"
            . "|\'$int $int\'\s*DAY_HOUR"
            . "|\'$int-$int\'\s*YEAR_MONTH"
            . "|\'$int:$int:$int\'\s*HOUR_SECOND"
            . "|\'$int $int:$int\'\s*DAY_MINUTE"
            . "|\'$int $int:$int:$int\'\s*DAY_SECOND"
            . ")\s*\$~i", $interval);
    }

    /**
     * Return list of columns for use in an SQL statement
     *
     * @param array $columns
     * @param array $fields info about the columns like in MyTableLister->fields (optional)
     * @return string
     */
    public function listColumns($columns, $fields = array())
    {
        $result = '';
        foreach ($columns as $column) {
            $result .= ',';
            $name = $this->escapeDbIdentifier($column);
            if (isset($fields[$column]['type']) && ($fields[$column]['type'] == 'set' || $fields[$column]['type'] == 'enum')) {
                $result .= "CAST($name AS integer) AS $name"; //NULLs will persist
            } else {
                $result .= $name;
            }
        }
        return substr($result, 1);
    }

    /**
     * Return if last error is a "duplicate entry"
     *
     * @return bool
     */
    public function errorDuplicateEntry()
    {
        return $this->errno == 1062;
    }

    /**
     * Execute an SQL and fetch the first row of a resultset.
     * If only one column is selected, return it, otherwise return whole row.
     *
     * @param string $sql SQL to be executed
     * @return mixed first selected row (or its first column if only one column is selected), null on empty SELECT, or false on error
     */
    public function fetchSingle($sql)
    {
        if ($query = $this->query($sql)) {
            $row = $query->fetch_assoc();
            if (count($row) > 1) {
                return $row;
            } elseif (is_array($row)) {
                return reset($row);
            } else {
                return null;
            }
        }
        return false;
    }

    /**
     * Execute an SQL, fetch and return all resulting rows
     *
     * @param string $sql
     * @return mixed array of associative arrays for each result row or empty array on error or no results
     */
    public function fetchAll($sql)
    {
        $result = array();
        $query = $this->query($sql);
        if (is_object($query) && is_a($query, '\mysqli_result')) {
            while ($row = $query->fetch_assoc()) {
                $result [] = $row;
            }
        }
        return $result;
    }

    /**
     * Execute an SQL, fetch resultset into an array reindexed by first field.
     * If the query selects only two fields, the first one is a key and the second one a value of the result array
     * Example: 'SELECT id,name FROM employees' --> [3=>"John", 4=>"Mary", 5=>"Joe"]
     * If the result set has more than two fields, whole resultset is fetched into each array item
     * Example: 'SELECT id,name,surname FROM employees' --> [3=>[name=>"John", surname=>"Smith"], [...]]
     * If the first column is non-unique, results are joined into an array.
     * Example: 'SELECT department_id,name FROM employees' --> [1=>['John', 'Mary'], 2=>['Joe','Pete','Sally']]
     * Example: 'SELECT division_id,name,surname FROM employees' --> [1=>[[name=>'John',surname=>'Doe'], [name=>'Mary',surname=>'Saint']], 2=>[...]]
     *
     * @param string $sql SQL to be executed
     * @return mixed - either associative array, empty array on empty SELECT, or false on error
     */
    public function fetchAndReindex($sql)
    {
        $query = $this->query($sql);
        if (!$query) {
            return false;
        }
        $result = array();
        while ($row = $query->fetch_assoc()) {
            $key = reset($row);
            $value = count($row) == 2 ? next($row) : $row;
            if (count($row) > 2) {
                array_shift($value);
            }
            if (isset($result[$key])) {
                if (is_array($value)) {
                    if (!is_array(reset($result[$key]))) {
                        $result[$key] = array($result[$key]);
                    }
                    $result[$key] [] = $value;
                } else {
                    $result[$key] = array_merge((array) $result[$key], (array) $value);
                }
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }

}