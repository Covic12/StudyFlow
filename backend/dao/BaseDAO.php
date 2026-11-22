<?php
declare(strict_types=1);

/** Shared, ownership-aware CRUD operations. Table and column names come only from DAO constants. */
abstract class BaseDAO
{
    public function __construct(protected PDO $pdo) {}

    abstract protected function table(): string;
    abstract protected function primaryKey(): string;
    /** @return list<string> */
    abstract protected function writableColumns(): array;

    protected function filterWritable(array $data): array
    {
        $allowed = array_flip($this->writableColumns());
        return array_intersect_key($data, $allowed);
    }

    public function create(array $data): int
    {
        $data = $this->filterWritable($data);
        if ($data === []) {
            throw new InvalidArgumentException('No writable fields were supplied.');
        }
        $columns = array_keys($data);
        $quoted = implode(', ', array_map(static fn($c) => "`{$c}`", $columns));
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $stmt = $this->pdo->prepare('INSERT INTO `' . $this->table() . '` (' . $quoted . ') VALUES (' . $placeholders . ')');
        $stmt->execute(array_values($data));
        return (int)$this->pdo->lastInsertId();
    }

    public function findById(int $id, ?int $userId = null): ?array
    {
        $sql = 'SELECT * FROM `' . $this->table() . '` WHERE `' . $this->primaryKey() . '` = ?';
        $params = [$id];
        if ($userId !== null && $this->primaryKey() !== 'user_id') {
            $sql .= ' AND user_id = ?';
            $params[] = $userId;
        }
        $stmt = $this->pdo->prepare($sql . ' LIMIT 1');
        $stmt->execute($params);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public function findAll(?int $userId = null, int $limit = 100, int $offset = 0): array
    {
        $limit = max(1, min($limit, 500));
        $offset = max(0, $offset);
        $sql = 'SELECT * FROM `' . $this->table() . '`';
        $params = [];
        if ($userId !== null && $this->primaryKey() !== 'user_id') {
            $sql .= ' WHERE user_id = ?';
            $params[] = $userId;
        }
        $sql .= ' ORDER BY `' . $this->primaryKey() . '` DESC LIMIT ' . $limit . ' OFFSET ' . $offset;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function update(int $id, array $data, ?int $userId = null): bool
    {
        $data = $this->filterWritable($data);
        if ($data === []) {
            throw new InvalidArgumentException('No writable fields were supplied.');
        }
        $sets = implode(', ', array_map(static fn($c) => "`{$c}` = ?", array_keys($data)));
        $sql = 'UPDATE `' . $this->table() . '` SET ' . $sets . ' WHERE `' . $this->primaryKey() . '` = ?';
        $params = [...array_values($data), $id];
        if ($userId !== null && $this->primaryKey() !== 'user_id') {
            $sql .= ' AND user_id = ?';
            $params[] = $userId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }

    public function delete(int $id, ?int $userId = null): bool
    {
        $sql = 'DELETE FROM `' . $this->table() . '` WHERE `' . $this->primaryKey() . '` = ?';
        $params = [$id];
        if ($userId !== null && $this->primaryKey() !== 'user_id') {
            $sql .= ' AND user_id = ?';
            $params[] = $userId;
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount() > 0;
    }
}
