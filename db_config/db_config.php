<?php

#[AllowDynamicProperties] class DB_CONFIG
{
    protected const string HOSTNAME = '';
    protected const string USERNAME = ''; // Set your database username
    protected const string PASSWORD = ''; // Set your database password
    protected const string DATABASE_NAME = ''; // Set your database name

    protected $conn;

    protected $dataSet;
    protected $sqlQuery;

    public function __construct($hostname, $username, $password, $databaseName)
    {
        $this->HOSTNAME = $hostname;
        $this->USERNAME = $username;
        $this->PASSWORD = $password;
        $this->DATABASE_NAME = $databaseName;

        $this->conn = mysqli_connect(self::HOSTNAME, self::USERNAME, self::PASSWORD, self::DATABASE_NAME);
        if (!$this->conn) {
            throw new Exception("MYSQLI connect error: " . mysqli_connect_error());
        }
    }

    public function disconnect()
    {
        mysqli_close($this->conn);
    }

    protected function escapeString($value): string
    {
        return mysqli_real_escape_string($this->conn, $value);
    }

    public function selectAll($tableName)
    {
        $this->sqlQuery = "SELECT * FROM " . self::DATABASE_NAME . "." . $this->escapeString($tableName);
        $this->dataSet = mysqli_query($this->conn, $this->sqlQuery);
        return $this->dataSet;
    }

    public function selectWhere($tableName, $condition, $extra = "")
    {
        $this->sqlQuery = 'SELECT * FROM ' . $tableName . ' WHERE ';
        if (is_array($condition)) {
            foreach ($condition as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($key !== 'cn') {
                        $this->sqlQuery .= $this->escapeString($key) . " " . $values['cn'] . "'";
                        $this->sqlQuery .= $this->escapeString($values[$key]);
                        $this->sqlQuery .= "' and ";
                    }
                }
            }
            $this->sqlQuery = substr($this->sqlQuery, 0, strlen($this->sqlQuery) - 4);
        } else {
            $this->sqlQuery .= $condition;
        }
        $this->dataSet = mysqli_query($this->conn, $this->sqlQuery);
        return $this->dataSet;
    }

    public function insertInto($tableName, $data = [])
    {
        $this->sqlQuery = 'INSERT INTO ' . $tableName;
        $columns = '(';
        $values = "(";
        foreach ($data as $key => $value) {
            $columns .= $this->escapeString($key) . ',';
            $values .= "'";
            $values .= $this->escapeString($value) . "',";
        }
        $columns = rtrim($columns, ',') . ')';
        $values = rtrim($values, ',') . ')';
        $this->sqlQuery .= $columns . ' VALUES ' . $values;
        return mysqli_query($this->conn, $this->sqlQuery);
    }

    public function deleteWhere($tableName, $condition = [], $extra = "")
    {
        $this->sqlQuery = 'DELETE FROM ' . $tableName . ' WHERE ';
        foreach ($condition as $values) {
            foreach ($values as $key => $value) {
                if ($key != 'cn') {
                    $this->sqlQuery .= $this->escapeString($key) . " " . $values['cn'] . "'";
                    $this->sqlQuery .= $this->escapeString($values[$key]) . "'";
                    $this->sqlQuery .= ' and ';
                }
            }
        }
        $this->sqlQuery = rtrim($this->sqlQuery, ' and ') . $extra;
        $this->dataSet = mysqli_query($this->conn, $this->sqlQuery);
        return $this->dataSet;
    }

    public function updateWhere($tableName, $values = [], $condition = [], $extra = "")
    {
        $this->sqlQuery = 'UPDATE ' . $tableName . ' SET ';
        foreach ($values as $key => $value) {
            $this->sqlQuery .= $this->escapeString($key) . "='" . $this->escapeString($value) . "',";
        }
        $this->sqlQuery = rtrim($this->sqlQuery, ',') . ' WHERE ';
        foreach ($condition as $keys => $val) {
            if ($keys != 'cn') {
                $this->sqlQuery .= $this->escapeString($keys) . "='" . $this->escapeString($val) . "' and ";
            }
        }
        $this->sqlQuery = rtrim($this->sqlQuery, ' and ') . $extra;
        return mysqli_query($this->conn, $this->sqlQuery);
    }

    public function withSqlQuery($query)
    {
        return mysqli_query($this->conn, $this->escapeString($query));
    }

    public function withSqlQueryWithoutEscapeString($query): mysqli_result|bool
    {
        return mysqli_query($this->conn, $query);
    }

    public function fetch(): false|array|null
    {
        if ($this->dataSet) {
            return mysqli_fetch_assoc($this->dataSet);
        }
        return false;
    }
}
