<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $c   = config('database');
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $c['host'],
            $c['port'],
            $c['database'],
            $c['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $c['username'], $c['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Koneksi database gagal: ' . $e->getMessage());
        }
    }

    public static function instance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }

    public function select(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function selectOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function insert(string $table, array $data): int
    {
        $columns      = implode(', ', array_map(static fn ($c) => "`{$c}`", array_keys($data)));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $sql          = "INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})";
        $this->execute($sql, array_values($data));
        return (int) $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, array $where): int
    {
        $set = implode(', ', array_map(static fn ($c) => "`{$c}` = ?", array_keys($data)));
        [$whereSql, $whereParams] = $this->buildWhere($where);
        $sql = "UPDATE `{$table}` SET {$set} WHERE {$whereSql}";
        return $this->execute($sql, array_merge(array_values($data), $whereParams));
    }

    public function delete(string $table, array $where): int
    {
        [$whereSql, $whereParams] = $this->buildWhere($where);
        return $this->execute("DELETE FROM `{$table}` WHERE {$whereSql}", $whereParams);
    }

    public function count(string $table, array $where = []): int
    {
        [$whereSql, $whereParams] = $this->buildWhere($where);
        $row = $this->selectOne("SELECT COUNT(*) AS total FROM `{$table}` WHERE {$whereSql}", $whereParams);
        return (int) ($row['total'] ?? 0);
    }

    public function buildWhere(array $where, string $empty = '1=1'): array
    {
        if ($where === []) {
            return [$empty, []];
        }
        $clauses = [];
        $params  = [];
        foreach ($where as $column => $value) {
            if ($value === null) {
                $clauses[] = "`{$column}` IS NULL";
            } else {
                $clauses[] = "`{$column}` = ?";
                $params[]  = $value;
            }
        }
        return [implode(' AND ', $clauses), $params];
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }
}