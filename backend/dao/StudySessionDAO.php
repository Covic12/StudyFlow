<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDAO.php';

final class StudySessionDAO extends BaseDAO
{
    protected function table(): string { return 'study_sessions'; }
    protected function primaryKey(): string { return 'session_id'; }
    protected function writableColumns(): array { return ['user_id', 'course_id', 'title', 'start_at', 'end_at', 'duration_minutes', 'reflection']; }
}
