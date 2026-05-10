<?php
// core/Model.php — Base Model (Active-Record inspired)

abstract class Model
{
    protected static string $table  = '';
    protected static string $pk     = 'id';
    protected PDO $db;

    public function __construct()
    {
        $this->db = App::db();
    }

    // ── Find by primary key ───────────────────────────────
    public function find(int $id): ?array
    {
        $sql  = 'SELECT * FROM `' . static::$table . '` WHERE `' . static::$pk . '` = ? LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // ── Get all ───────────────────────────────────────────
    public function all(string $orderBy = ''): array
    {
        $sql = 'SELECT * FROM `' . static::$table . '`';
        if ($orderBy) $sql .= ' ORDER BY ' . $orderBy;
        return $this->db->query($sql)->fetchAll();
    }

    // ── Generic where ─────────────────────────────────────
    public function where(array $conditions, string $orderBy = '', int $limit = 0): array
    {
        $clauses = [];
        $values  = [];
        foreach ($conditions as $col => $val) {
            $clauses[] = "`{$col}` = ?";
            $values[]  = $val;
        }
        $sql = 'SELECT * FROM `' . static::$table . '` WHERE ' . implode(' AND ', $clauses);
        if ($orderBy) $sql .= ' ORDER BY ' . $orderBy;
        if ($limit)   $sql .= ' LIMIT ' . (int)$limit;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return $stmt->fetchAll();
    }

    public function findWhere(array $conditions): ?array
    {
        $rows = $this->where($conditions, '', 1);
        return $rows[0] ?? null;
    }

    // ── Insert ────────────────────────────────────────────
    public function insert(array $data): int
    {
        $cols = implode('`, `', array_keys($data));
        $phs  = implode(', ', array_fill(0, count($data), '?'));
        $sql  = "INSERT INTO `" . static::$table . "` (`{$cols}`) VALUES ({$phs})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        return (int)$this->db->lastInsertId();
    }

    // ── Update ────────────────────────────────────────────
    public function update(int $id, array $data): bool
    {
        $sets = implode(', ', array_map(fn($col) => "`{$col}` = ?", array_keys($data)));
        $sql  = "UPDATE `" . static::$table . "` SET {$sets} WHERE `" . static::$pk . "` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([...array_values($data), $id]);
    }

    // ── Delete ────────────────────────────────────────────
    public function delete(int $id): bool
    {
        $sql  = "DELETE FROM `" . static::$table . "` WHERE `" . static::$pk . "` = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ── Count ─────────────────────────────────────────────
    public function count(array $conditions = []): int
    {
        $sql = 'SELECT COUNT(*) FROM `' . static::$table . '`';
        $values = [];
        if ($conditions) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "`{$col}` = ?";
                $values[]  = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        return (int)$stmt->fetchColumn();
    }

    // ── Raw query helper ──────────────────────────────────
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function queryOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch() ?: null;
    }

    public function execute(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
}
