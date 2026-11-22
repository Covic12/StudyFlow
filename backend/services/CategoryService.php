<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
final class CategoryService extends BaseService
{
    protected function requiredFields(): array { return ['user_id', 'name']; }
    protected function fieldRules(): array { return ['name' => 70, 'color' => 7]; }
}
