<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseDAO.php';

final class NoteDAO extends BaseDAO
{
    protected function table(): string { return 'notes'; }
    protected function primaryKey(): string { return 'note_id'; }
    protected function writableColumns(): array { return ['user_id', 'course_id', 'category_id', 'title', 'body']; }
}
