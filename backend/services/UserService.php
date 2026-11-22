<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
final class UserService extends BaseService
{
    protected function requiredFields(): array { return ['full_name', 'email']; }
    protected function fieldRules(): array { return ['full_name' => 120, 'email' => 190, 'role' => ['student', 'admin']]; }
    protected function prepare(array $data): array
    {
        unset($data['password_hash']); // Never accept a caller-supplied password hash.
        if (isset($data['password'])) {
            if (strlen((string)$data['password']) < 8) throw new InvalidArgumentException(json_encode(['password'=>'Password must contain at least 8 characters.']));
            $data['password_hash'] = password_hash((string)$data['password'], PASSWORD_DEFAULT);
            unset($data['password']);
        }
        return $data;
    }
    public function create(array $data): int
    {
        if (empty($data['password'])) throw new InvalidArgumentException(json_encode(['password'=>'Password is required.']));
        $data = $this->validate($data);
        $data = $this->prepare($data);
        return $this->dao->create($data);
    }
}
