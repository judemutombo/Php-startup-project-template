<?php

namespace App\Database;

use PDO;
use PDOStatement;

abstract class Database{
    protected $host;
    protected $username;
    protected $dbname;
    protected $password;
    protected $type;
    protected $pdo = null;

    protected static $config;
    protected static $instance = null;

    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$config = require ROOT."/App/config/config.php";
            self::$instance = new Database();
        }
        return self::$instance;
    }

    protected function __construct()
    {
        $this->host = self::$config["hostname"];
        $this->username = self::$config["username"];
        $this->dbname = self::$config["dbname"];
        $this->password = self::$config["password"];
        $this->type = self::$config["type"];
    }

    public function getDbType() : string
    {
        return $this->type;
    }

    abstract protected function getPDO() : PDO;

    abstract public function select(string $table, array $columns = [], array $clauses = [], array $clauseLinkers = [], array $parameters = []) : array;

    abstract public function insert(string $table, array $columns = [], array $parameters = []) : bool;

    abstract public function update(string $table, array $columns = [], array $clauses = [], array $clauseLinkers = [], array $parameters = []);
    
}