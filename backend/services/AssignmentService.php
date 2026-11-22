<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
final class AssignmentService extends BaseService
{
    protected function requiredFields(): array { return ['user_id', 'course_id', 'title']; }
    protected function fieldRules(): array { return ['title' => 180, 'priority' => ['low', 'medium', 'high'], 'status' => ['pending', 'in_progress', 'completed']]; }
}
