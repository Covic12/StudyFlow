<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDAO.php';

final class UserDAO extends BaseDAO
{
    protected function table(): string { return 'users'; }
    protected function primaryKey(): string { return 'user_id'; }
    protected function writableColumns(): array { return ['full_name', 'email', 'password_hash', 'role']; }
}
