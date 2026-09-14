<?php

declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    protected string $table = '';
    protected string $primaryKey = 'id';

    protected function db(): Database
    {
        return Database::instance();
    }

    public function all(string $orderBy = ''): array
    {
        $sql = "SELECT * FROM `{$this->table}`" . ($orderBy !== '' ? " ORDER BY {$orderBy}" : '');
        return $this->db()->select($sql);
    }

    public function find($id): ?array
    {
        return $this->db()->selectOne(
            "SELECT * FROM `{$this->table}` WHERE `{$this->primaryKey}` = ?",
            [$id]
        );
    }

    public function where(array $where): array
    {
        [$sql, $params] = $this->db()->buildWhere($where);
        return $this->db()->select("SELECT * FROM `{$this->table}` WHERE {$sql}", $params);
    }

    public function first(array $where): ?array
    {
        [$sql, $params] = $this->db()->buildWhere($where);
        return $this->db()->selectOne("SELECT * FROM `{$this->table}` WHERE {$sql} LIMIT 1", $params);
    }

    public function create(array $data): int
    {
        return $this->db()->insert($this->table, $data);
    }

    public function updateById($id, array $data): int
    {
        return $this->db()->update($this->table, $data, [$this->primaryKey => $id]);
    }

    public function deleteById($id): int
    {
        return $this->db()->delete($this->table, [$this->primaryKey => $id]);
    }

    public function count(array $where = []): int
    {
        return $this->db()->count($this->table, $where);
    }
}