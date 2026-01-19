<?php

namespace App\Database;

use App\General;
use PDO;

class Database
{
    readonly string $host;
    readonly string $database;
    readonly string $username;
    readonly string $password;

    private PDO $pdo;

    public function __construct(bool $isCli = false) {
        if (General::isLocal() || General::isDev() || $isCli) {
            $this->host = $_ENV["DEV_DB_HOST"];
            $this->database = $_ENV["DEV_DB_NAME"];
            $this->username = $_ENV["DEV_DB_USER"];
            $this->password = $_ENV["DEV_DB_PASS"];
        }
        else {
            $this->host = $_ENV["DB_HOST"];
            $this->database = $_ENV["DB_NAME"];
            $this->username = $_ENV["DB_USER"];
            $this->password = $_ENV["DB_PASS"];
        }

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $dsn = "mysql:host=$this->host;dbname=$this->database;charset=utf8mb4";
        $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
    }

    public static function CLI() : self
    {
        return new self(true);
    }

    public static function sqlQuery(string $sql, array $params = []) : array {
        $instance = new self();
        $stmt = $instance->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue(":$key", $value, PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /** Inserts an insertable object into the database. Returns the object's
     * insert id.
     *
     * @param Insertable $object
     * @return int
     */
    public static function storeNewObject(Insertable $object) : int {
        // Ensure the object's id is set to -1, indicating it is a new record
        if ($object->id !== -1) {
            // todo: Build the update object method
            throw new \InvalidArgumentException("Object's id not set to -1. Is this an update request?");
        }

        $instance = new self();
        $sql = "INSERT INTO {$object->getTableName()} (";

        // build the col names
        for ($i = 0; $i < count($object->insertableColumns()); $i++) {
            $keys = array_keys($object->insertableColumns());
            $colName = $keys[$i];
            $sql .= $colName;

            if ($i < count($object->insertableColumns()) - 1) {
                $sql .= ", ";
            }
        }
        $sql .= ") VALUES (";
        // build the values
        for ($i = 0; $i < count($object->insertableColumns()); $i++) {
            $values = array_values($object->insertableColumns());
            $colValue = $values[$i];

            if (gettype($colValue) == "boolean") {
                $sql .= $colValue ? 1 : 0;
            } else if (gettype($colValue) == "integer") {
                $sql .= $colValue;
            } else {
                $sql .= "'" . $colValue . "'";
            }

            if ($i < count($object->insertableColumns()) - 1) {
                $sql .= ", ";
            }
        }
        $sql .= ')';

        $stmt = $instance->pdo->prepare($sql);
        $stmt->execute();

        return $instance->pdo->lastInsertId();
    }
}
