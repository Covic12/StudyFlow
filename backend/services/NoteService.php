<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
final class NoteService extends BaseService
{
    protected function requiredFields(): array { return ['user_id', 'title', 'body']; }
    protected function fieldRules(): array { return ['title' => 180]; }
}
