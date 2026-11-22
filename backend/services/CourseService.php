<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
final class CourseService extends BaseService
{
    protected function requiredFields(): array { return ['user_id', 'course_name', 'course_code']; }
    protected function fieldRules(): array { return ['course_name' => 150, 'course_code' => 30, 'instructor' => 120, 'semester' => 40, 'progress' => 100]; }
}
