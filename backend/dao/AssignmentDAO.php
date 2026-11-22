<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDAO.php';

final class AssignmentDAO extends BaseDAO
{
    protected function table(): string { return 'assignments'; }
    protected function primaryKey(): string { return 'assignment_id'; }
    protected function writableColumns(): array { return ['user_id', 'course_id', 'category_id', 'title', 'description', 'due_date', 'priority', 'status']; }
}
