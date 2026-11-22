<?php
declare(strict_types=1);
require_once __DIR__ . '/../dao/DAOFactory.php';
require_once __DIR__ . '/UserService.php';
require_once __DIR__ . '/CourseService.php';
require_once __DIR__ . '/AssignmentService.php';
require_once __DIR__ . '/StudySessionService.php';
require_once __DIR__ . '/NoteService.php';
require_once __DIR__ . '/CategoryService.php';
final class ServiceFactory
{
    public static function users(): UserService { return new UserService(DAOFactory::users()); }
    public static function courses(): CourseService { return new CourseService(DAOFactory::courses()); }
    public static function assignments(): AssignmentService { return new AssignmentService(DAOFactory::assignments()); }
    public static function studySessions(): StudySessionService { return new StudySessionService(DAOFactory::studySessions()); }
    public static function notes(): NoteService { return new NoteService(DAOFactory::notes()); }
    public static function categories(): CategoryService { return new CategoryService(DAOFactory::categories()); }
}
