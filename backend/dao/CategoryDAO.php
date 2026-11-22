<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDAO.php';

final class CategoryDAO extends BaseDAO
{
    protected function table(): string { return 'categories'; }
    protected function primaryKey(): string { return 'category_id'; }
    protected function writableColumns(): array { return ['user_id', 'name', 'color']; }
}
