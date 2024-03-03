<?php

namespace Database\Configs;

use App\Functions\Core;
use PDO;
use PDOException;

class DB extends Core
{
    private ?string $host;
    private ?string $user;
    private ?string $password;
    private ?string $database;
    private PDO $pdo;

    public function __construct()
    {
        $this->host = self::getEnvVariable("HOSTNAME");
        $this->user = self::getEnvVariable("USERNAME");
        $this->password = self::getEnvVariable("PASSWORD");
        $this->database = self::getEnvVariable("DATABASE_NAME");

        $dsn = "mysql:host={$this->host};dbname={$this->database};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new PDOException("Failed to connect to the database.", (int)$e->getCode());
        }
    }

    /**
     * @param string $table
     * @param array $data
     * @return false|string
     */
    public function insert(string $table, array $data): false|string
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $sql = "INSERT INTO" . $table . " (" . $columns . ") VALUES (" . $values . ")";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            error_log("Database insert error: " . $e->getMessage());
            // Re-throw the exception to let the calling code handle it
            throw new PDOException("Failed to insert data into the database.", (int)$e->getCode());
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function get(string $table, int $id): mixed
    {
        try {
            $sql = "SELECT * FROM" . $table . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            error_log("Database get error: " . $e->getMessage());
            // Re-throw the exception to let the calling code handle it
            throw new PDOException("Failed to retrieve data from the database.", (int)$e->getCode());
        }
    }

    /**
     * @param string $table
     * @return array
     */
    public function getAll(string $table) : array
    {
        try {
            $sql = "SELECT * FROM".$table;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Database get error: " . $e->getMessage());
            throw new PDOException("Failed to retrieve data from the database.", (int)$e->getCode());
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @param array $data
     * @return int
     */
    public function update(string $table, int $id, array $data): int
    {
        try {
            $set = "";
            foreach ($data as $key => $value) {
                $set .= "$key = :$key, ";
            }
            $set = rtrim($set, ', ');
            $sql = "UPDATE " . $table . " SET " . $set . " WHERE id = :id";
            $data['id'] = $id;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            error_log("Database update error: " . $e->getMessage());
            // Re-throw the exception to let the calling code handle it
            throw new PDOException("Failed to update data in the database.", (int)$e->getCode());
        }
    }

    /**
     * @param string $table
     * @param int $id
     * @return int
     */
    public function delete(string $table, int $id): int
    {
        try {
            $sql = "DELETE FROM" . $table . " WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            // Log or handle the error appropriately
            error_log("Database delete error: " . $e->getMessage());
            // Re-throw the exception to let the calling code handle it
            throw new PDOException("Failed to delete data from the database.", (int)$e->getCode());
        }
    }

    /**
     * @param $table
     * @return bool
     */
    public function deleteAll($table) : bool
    {
        try {
            $sql = "DELETE FROM" . $table;
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Database delete error: " . $e->getMessage());
            throw new PDOException("Failed to delete data from the database.", (int)$e->getCode());
        }
    }

    /**
     * Execute a simple SQL query without parameters
     *
     * @param string $sql The SQL query
     * @return array|false The result of the query
     * @throws PDOException If query execution fails
     */
    public function executeSimpleQuery(string $sql): false|array
    {
        try {
            return $this->pdo->query($sql)->fetchAll();
        } catch (PDOException $e) {
            error_log("Database query error: " . $e->getMessage());
            throw new PDOException("Failed to execute SQL query.", (int)$e->getCode());
        }
    }
}
