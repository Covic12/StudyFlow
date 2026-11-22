<?php
declare(strict_types=1);

/** Application rules live here; route handlers never call DAOs directly. */
abstract class BaseService
{
    public function __construct(protected object $dao) {}
    abstract protected function requiredFields(): array;
    abstract protected function fieldRules(): array;

    protected function validate(array $data, bool $partial = false): array
    {
        $errors = [];
        if (!$partial) foreach ($this->requiredFields() as $field) {
            if (!array_key_exists($field, $data) || $data[$field] === '' || $data[$field] === null) $errors[$field] = 'This field is required.';
        }
        foreach ($this->fieldRules() as $field => $rule) {
            if (!array_key_exists($field, $data) || $data[$field] === null || $data[$field] === '') continue;
            if (is_int($rule) && (function_exists('mb_strlen') ? mb_strlen((string)$data[$field]) : strlen((string)$data[$field])) > $rule) $errors[$field] = "Must not exceed {$rule} characters.";
            elseif (is_array($rule) && !in_array($data[$field], $rule, true)) $errors[$field] = 'Invalid value.';
        }
        if (isset($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'A valid email address is required.';
        foreach (['user_id', 'course_id', 'category_id'] as $idField) {
            if (isset($data[$idField]) && (!filter_var($data[$idField], FILTER_VALIDATE_INT) || (int)$data[$idField] < 1)) $errors[$idField] = 'Must be a positive integer.';
        }
        if (isset($data['progress']) && (!is_numeric($data['progress']) || (int)$data['progress'] < 0 || (int)$data['progress'] > 100)) $errors['progress'] = 'Progress must be between 0 and 100.';
        if (isset($data['color']) && !preg_match('/^#[0-9A-Fa-f]{6}$/', (string)$data['color'])) $errors['color'] = 'Color must be a six-digit hex value.';
        if (isset($data['duration_minutes']) && (!filter_var($data['duration_minutes'], FILTER_VALIDATE_INT) || (int)$data['duration_minutes'] < 1)) $errors['duration_minutes'] = 'Duration must be a positive integer.';
        if (isset($data['start_at'], $data['end_at']) && strtotime((string)$data['end_at']) <= strtotime((string)$data['start_at'])) $errors['end_at'] = 'End time must be after start time.';
        if ($errors) throw new InvalidArgumentException(json_encode($errors, JSON_UNESCAPED_UNICODE));
        return $data;
    }
    public function all(?int $ownerId = null): array { return $this->sanitizeMany($this->dao->findAll($ownerId)); }
    public function get(int $id, ?int $ownerId = null): ?array { $row=$this->dao->findById($id,$ownerId); return $row ? $this->sanitize($row) : null; }
    public function create(array $data): int { $data=$this->validate($data); return $this->dao->create($this->prepare($data)); }
    public function update(int $id,array $data,?int $ownerId=null): bool { if (!$data) throw new InvalidArgumentException('Request body cannot be empty.'); return $this->dao->update($id,$this->prepare($this->validate($data,true)),$ownerId); }
    public function delete(int $id,?int $ownerId=null): bool { return $this->dao->delete($id,$ownerId); }
    protected function prepare(array $data): array { return $data; }
    protected function sanitize(array $row): array { unset($row['password_hash']); return $row; }
    protected function sanitizeMany(array $rows): array { return array_map(fn($r)=>$this->sanitize($r),$rows); }
}
