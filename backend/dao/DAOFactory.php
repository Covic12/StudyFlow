<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/UserDAO.php';
require_once __DIR__ . '/CourseDAO.php';
require_once __DIR__ . '/AssignmentDAO.php';
require_once __DIR__ . '/StudySessionDAO.php';
require_once __DIR__ . '/NoteDAO.php';
require_once __DIR__ . '/CategoryDAO.php';

final class DAOFactory
{
    public static function users(): UserDAO { return new UserDAO(Database::connection()); }
    public static function courses(): CourseDAO { return new CourseDAO(Database::connection()); }
    public static function assignments(): AssignmentDAO { return new AssignmentDAO(Database::connection()); }
    public static function studySessions(): StudySessionDAO { return new StudySessionDAO(Database::connection()); }
    public static function notes(): NoteDAO { return new NoteDAO(Database::connection()); }
    public static function categories(): CategoryDAO { return new CategoryDAO(Database::connection()); }
}
