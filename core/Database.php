<?php

namespace Core;

use PDO;
use Core\Database\QueryBuilder;
use Core\Exceptions\DatabaseException;

class Database
{
    private PDO $pdo;
    private static array $connections = [];
    private QueryBuilder $queryBuilder;
    private int $transactionLevel = 0;

    public function __construct(array $config)
    {
        $dsn = $this->buildDsn($config);
        
        if (!isset(self::$connections[$dsn])) {
            self::$connections[$dsn] = $this->createConnection($config);
        }
        
        $this->pdo = self::$connections[$dsn];
        $this->queryBuilder = new QueryBuilder();
    }

    private function buildDsn(array $config): string
    {
        return sprintf(
            "%s:host=%s;port=%s;dbname=%s;charset=%s",
            $config['driver'],
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );
    }

    private function createConnection(array $config): PDO
    {
        try {
            $pdo = new PDO(
                $this->buildDsn($config),
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => true
                ]
            );

            if ($config['strict']) {
                $pdo->exec('SET SESSION sql_mode = STRICT_ALL_TABLES');
            }

            return $pdo;
        } catch (\PDOException $e) {
            throw new DatabaseException("Could not connect to database: " . $e->getMessage());
        }
    }

    public function transaction(\Closure $callback)
    {
        try {
            $this->beginTransaction();
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }

    public function beginTransaction(): bool
    {
        if ($this->transactionLevel === 0) {
            $this->pdo->beginTransaction();
        } else {
            $this->pdo->exec("SAVEPOINT trans{$this->transactionLevel}");
        }

        $this->transactionLevel++;
        return true;
    }

    public function commit(): bool
    {
        $this->transactionLevel--;

        if ($this->transactionLevel === 0) {
            return $this->pdo->commit();
        }

        return true;
    }

    public function rollBack(): bool
    {
        if ($this->transactionLevel === 1) {
            $this->transactionLevel = 0;
            return $this->pdo->rollBack();
        }

        $this->transactionLevel--;
        $this->pdo->exec("ROLLBACK TO SAVEPOINT trans{$this->transactionLevel}");
        return true;
    }

    public function raw(string $sql, array $params = [])
    {
        return $this->query($sql, $params);
    }

    public function insert(string $table, array $data): bool
    {
        $columns = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$values})";
        
        return $this->query($sql, array_values($data))->rowCount() > 0;
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }
} 