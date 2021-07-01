<?php

/**
 * Class PHPEasy - PDO extension abstraction
 * @author Jeterson Lordano <https://github.com/jetersonlordano>
 */

namespace Models\Database;

use PDO;
use PDOException;

class PDOEasy
{

    /** @var string */
    public $query;

    /**  @var array */
    public $params;

    /** @var string */
    private $host;

    /** @var string */
    private $dbname;

    /** @var string */
    private $port;

    /** @var string */
    private $user;

    /** @var string */
    private $psw;

    /** @var object */
    private $statement;

    /** @var object */
    private $connection;

    /** @var string */
    private $error;

    /**
     * @param array $config
     */
    public function __construct(array $config = null)
    {
        $this->host   = $config['host'] ?? DATA_BASE['host'];
        $this->dbname = $config['db']   ?? DATA_BASE['db'];
        $this->port   = $config['port'] ?? DATA_BASE['port'];
        $this->user   = $config['user'] ?? DATA_BASE['user'];
        $this->psw    = $config['psw']  ?? DATA_BASE['psw'];
    }

    /**
     * SQL Select
     * @param string $table
     * @param string $columns
     * @param boolean $found_rows
     */
    public function select(string $table, string $columns = '*', bool $found_rows = false)
    {
        $this->query = "SELECT " . ($found_rows ? "SQL_CALC_FOUND_ROWS " : null) . "{$columns} FROM {$table}";
    }

    /**
     * SQL Insert
     * @param string $table
     */
    public function insert(string $table)
    {
        $columns = implode(", ", array_keys($this->params));
        $params  = str_replace(', ', ', :', $columns);
        $this->query = "INSERT INTO {$table} ({$columns}) values (:{$params})";
    }

    /**
     * SQL Update
     * @param string $table
     * @param string $columns
     */
    public function update(string $table, string $columns)
    {
        $this->query = "UPDATE {$table} SET {$columns}";
    }

    /**
     * SQL Delete
     * @param string $table
     */
    public function delete(string $table)
    {
        $this->query = "DELETE FROM {$table}";
    }

    /**
     * SQL Join
     * @param string $table
     * @param string $primary_key
     * @param string $foreign_key
     * @param string $type
     */
    public function join(string $table, string $primary_key, string $foreign_key, string $type = 'INNER JOIN')
    {
        $this->query .= " {$type} {$table} ON {$primary_key} = {$foreign_key}";
    }

    /**
     * SQL Where
     * @param string $terms
     */
    public function where(string $terms)
    {
        $this->query .= (strpos($this->query, 'WHERE') === false ? " WHERE" : null) . " {$terms}";
    }

    /**
     * SQL Group
     * @param string $columns
     */
    public function group(string $group_by)
    {
        $this->query .= " GROUP BY {$group_by}";
    }

    /**
     * SQL ORDER
     * @param string $columns
     */
    public function order(string $order_by)
    {
        $this->query .= " ORDER BY {$order_by}";
    }

    /**
     * SQL Limit
     * @param integer $limit
     * @param integer $offset
     */
    public function limit(int $limit = 1, int $offset = null)
    {
        $this->query .= " LIMIT " . (is_null($offset) ? "{$limit}" : "{$offset}, {$limit}");
    }

    /**
     * @return array|null
     */
    public function fetchAll(): ?array
    {
        return $this->statement->fetchAll();
    }

    /**
     * @return int
     */
    public function rowCount(): int
    {
        return $this->statement->rowCount();
    }

    /**
     * Count Rows pagination
     * Use SQL_CALC_FOUND_ROWS in Select
     * @return integer
     */
    public function foundRows(): int
    {
        $this->query = "SELECT FOUND_ROWS()";
        $this->exec();
        return $this->fetchAll()[0]['FOUND_ROWS()'];
    }

    /**
     * @return mixed
     */
    public function debug()
    {
        return $this->error;
    }

    /**
     * SQL Execute
     * @return boolean
     */
    public function exec(): bool
    {

        $this->connection = $this->connect();

        if (!$this->connection) {
            return false;
        }

        if (!$this->query) {
            return $this->connection;
        }

        $this->statement = $this->connection->prepare($this->query);

        foreach ($this->params ?? [] as $key => $value) {
            $this->statement->bindParam(":" . $key, $this->params[$key], PDO::PARAM_STR);
        }

        return $this->safe() ? $this->statement->execute() : false;
    }

    /**
     * @return boolean
     */
    private function safe(): bool
    {
        if (strpos($this->query, 'DELETE') === false && strpos($this->query, 'UPDATE') === false) {
            return true;
        }

        if (strpos($this->query, 'WHERE') === false || strpos($this->query, 'LIMIT') === false) {
            $this->error = "WHERE and LIMIT parameters are required";
            return false;
        }

        return true;
    }

    /**
     * @return PDO||null
     */
    private function connect(): ?PDO
    {
        if (!$this->connection) {
            try {
                $this->connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};port={$this->port};",
                    $this->user,
                    $this->psw,
                    [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES UTF8",
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_CASE => PDO::CASE_NATURAL,
                    ]
                );
            } catch (PDOException $exception) {
                $this->error = $exception;
            }
        }
        return $this->connection;
    }
}
