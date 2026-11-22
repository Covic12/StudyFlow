<?php
declare(strict_types=1);

require_once __DIR__ . '/../backend/dao/DAOFactory.php';

/** Integration smoke test. Uses a transaction and rolls back all test records. */
$pdo = Database::connection();
$pdo->beginTransaction();
try {
    $suffix = bin2hex(random_bytes(4));
    $userId = DAOFactory::users()->create([
        'full_name' => 'DAO Test User',
        'email' => "dao-test-{$suffix}@example.test",
        'password_hash' => password_hash('temporary-test-password', PASSWORD_DEFAULT),
        'role' => 'student',
    ]);
    $assert = static function (bool $condition, string $message): void {
        if (!$condition) { throw new RuntimeException($message); }
    };

    $user = DAOFactory::users()->findById($userId);
    $assert($user !== null, 'User create/read failed.');
    $assert(DAOFactory::users()->update($userId, ['full_name' => 'DAO Test Updated']), 'User update failed.');

    $categoryId = DAOFactory::categories()->create(['user_id' => $userId, 'name' => 'Test category', 'color' => '#123456']);
    $courseId = DAOFactory::courses()->create(['user_id' => $userId, 'course_name' => 'Test course', 'course_code' => "T-{$suffix}", 'progress' => 10]);
    $assignmentId = DAOFactory::assignments()->create(['user_id' => $userId, 'course_id' => $courseId, 'category_id' => $categoryId, 'title' => 'Test assignment', 'priority' => 'high', 'status' => 'pending']);
    $sessionId = DAOFactory::studySessions()->create(['user_id' => $userId, 'course_id' => $courseId, 'title' => 'Test session', 'start_at' => '2026-11-22 09:00:00', 'end_at' => '2026-11-22 10:00:00', 'duration_minutes' => 60]);
    $noteId = DAOFactory::notes()->create(['user_id' => $userId, 'course_id' => $courseId, 'category_id' => $categoryId, 'title' => 'Test note', 'body' => 'DAO integration test']);

    foreach ([
        [DAOFactory::categories(), $categoryId], [DAOFactory::courses(), $courseId],
        [DAOFactory::assignments(), $assignmentId], [DAOFactory::studySessions(), $sessionId],
        [DAOFactory::notes(), $noteId],
    ] as [$dao, $id]) {
        $assert($dao->findById($id, $userId) !== null, 'Entity read failed for ID ' . $id);
        $assert($dao->findAll($userId) !== [], 'Entity list failed for ID ' . $id);
        $assert($dao->update($id, $dao === null ? [] : match (true) {
            $dao instanceof CategoryDAO => ['name' => 'Updated category'],
            $dao instanceof CourseDAO => ['progress' => 20],
            $dao instanceof AssignmentDAO => ['status' => 'in_progress'],
            $dao instanceof StudySessionDAO => ['reflection' => 'Updated'],
            $dao instanceof NoteDAO => ['body' => 'Updated note'],
            default => [],
        }, $userId), 'Entity update failed for ID ' . $id);
    }

    // Confirm owner scoping prevents a different user's ID from being read or modified.
    $assert(DAOFactory::courses()->findById($courseId, $userId + 999999) === null, 'Owner scoping read test failed.');
    $assert(!DAOFactory::courses()->delete($courseId, $userId + 999999), 'Owner scoping delete test failed.');

    // Delete in dependency order to exercise DELETE operations without FK violations.
    $assert(DAOFactory::notes()->delete($noteId, $userId), 'Note delete failed.');
    $assert(DAOFactory::studySessions()->delete($sessionId, $userId), 'Session delete failed.');
    $assert(DAOFactory::assignments()->delete($assignmentId, $userId), 'Assignment delete failed.');
    $assert(DAOFactory::categories()->delete($categoryId, $userId), 'Category delete failed.');
    $assert(DAOFactory::courses()->delete($courseId, $userId), 'Course delete failed.');
    $assert(DAOFactory::users()->delete($userId), 'User delete failed.');

    $pdo->rollBack();
    echo "PASS: DAO create/read/update/delete and owner-scope smoke tests completed.\n";
} catch (Throwable $e) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    fwrite(STDERR, 'FAIL: ' . $e->getMessage() . "\n");
    exit(1);
}
