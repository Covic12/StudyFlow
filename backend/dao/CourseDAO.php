<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDAO.php';

final class CourseDAO extends BaseDAO
{
    protected function table(): string { return 'courses'; }
    protected function primaryKey(): string { return 'course_id'; }
    protected function writableColumns(): array { return ['user_id', 'course_name', 'course_code', 'instructor', 'semester', 'progress']; }
}
