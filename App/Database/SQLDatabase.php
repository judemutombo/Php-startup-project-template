<?php

namespace App\Database;

use Exception;
use PDO;
use PDOStatement;

class SQLDatabase extends Database{

    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$config = require ROOT."/App/config/config.php";
            self::$instance = new SQLDatabase();
        }
        return self::$instance;
    }

    private function __construct(){
        parent::__construct();
    }

    #[\Override]
    protected function getPDO()  : PDO
    {
        if($this->pdo == null)
        {
            switch ($this->type) {
                case "MySql"  :
                    $this->pdo = new PDO("mysql:host=".$this->host.";dbname=".$this->dbname, $this->username, $this->password);
                    break;
                case "Lite":
                    $this->pdo = new PDO("sqlite:". $this->host);
                default:
                    throw new Exception("Undefined database type");
                    break;
            }
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;

    }

    #[\Override]
    public function select(string $table, array $columns = [],  array $clauses = [], array $clauseLinkers = [], array $parameters = []) : array{
        if(count($clauses) != count($parameters)){
            throw new \InvalidArgumentException("The number of clauses must match the number of parameters.");
        }

        if(count($clauses) - 1 != count($clauseLinkers) ){
            throw new \InvalidArgumentException("The number of linkers does not match.");
        }

        $query = "SELECT ".(empty($columns) ? "* " : implode(", ", $columns));
 
        $query = $query." FROM ".$table." ";

        if(!empty($clauses)){
            $query = $query." WHERE ";
            foreach($clauses as $clause){
                if (!isset($clause["column"], $clause["condition"])) {
                    throw new \InvalidArgumentException("Each clause must contain 'column' and 'condition' keys.");
                }
                $query = $query.$clause["column"]." ";
                $query = $query.$clause["condition"]." ? ";

                if(($value = current($clauseLinkers)) !== null){
                    $query = $query.$value." "; 
                    next($clauseLinkers);
                }
            }
        } 
       
        try {
            $result = null;
            if(empty($parameters)){
                $result= $this->getPDO()->query($query);
                $data = $result->fetchAll(PDO::FETCH_OBJ);
                return $data;
            }else{
                $result = $this->getPDO()->prepare($query);
                $result->execute($parameters);
                $data = $result->fetchAll(PDO::FETCH_OBJ);
                return $data;
            }
        } catch (\PDOException $e) {
            error_log("Insert Error: " . $e->getMessage());
            return [];
        }
    }

    #[\Override]
    public function insert(string $table, array $columns = [], array $parameters = []) : bool{

        if (count($columns) != count($parameters)) {
            throw new \InvalidArgumentException("Column count does not match parameter count.");
        }
        $query = "INSERT INTO `{$table}`";
        if(!empty($columns)){
            $query = $query."(".implode(", ", $columns).") ";
        }

        $query = $query."VALUES (".implode(", ", array_fill(0, count($parameters), "?")).")";

        $result = $this->getPDO()->prepare($query);
        $result->execute($parameters);

        try {
            $stmt = $this->getPDO()->prepare($query);
            $stmt->execute($parameters);
            return $stmt->rowCount() >= 1;
        } catch (\PDOException $e) {
            error_log("Insert Error: " . $e->getMessage());
            return false;
        }
    }

    #[\Override]
    public function update(string $table, array $columns = [], array $clauses = [], array $clauseLinkers = [], array $parameters = []) : bool{
        if(count($columns) + count($clauses) != count($parameters)){
            throw new \InvalidArgumentException("The number of of parameters does not match.");
        }

        if(!empty($clauses)){
            if(count($clauses) - 1 != count($clauseLinkers) ){
                throw new \InvalidArgumentException("The number of linkers does not match.");
            }
        }

        $query = "UPDATE `$table` SET ";

        if(empty($columns)){
            throw new \InvalidArgumentException("Empty columns");
        }else{
            $query = $query.implode(" = ?, ", $columns);
            $query = $query." = ? ";
        }

        if(!empty($clauses)){
            $query = $query." WHERE ";
            foreach($clauses as $clause){
                if (!isset($clause["column"], $clause["condition"])) {
                    throw new \InvalidArgumentException("Each clause must contain 'column' and 'condition' keys.");
                }
                $query = $query.$clause["column"]." ";
                $query = $query.$clause["condition"]." ? ";

                if(($value = current($clauseLinkers)) !== null){
                    $query = $query.$value." "; 
                    next($clauseLinkers);
                }
            }
        }

        try {
            $stmt = $this->getPDO()->prepare($query);
            $stmt->execute($parameters);
            return $stmt->rowCount() >= 1;
        } catch (\PDOException $e) {
            error_log("Insert Error: " . $e->getMessage());
            return false;
        }
    }


}