<?php
declare(strict_types=1);
require_once __DIR__ . '/../backend/services/UserService.php';
require_once __DIR__ . '/../backend/services/CourseService.php';

final class FakeDAO
{
    public array $created = [];
    public function create(array $data): int { $this->created = $data; return 41; }
    public function findAll(?int $ownerId=null): array { return []; }
    public function findById(int $id, ?int $ownerId=null): ?array { return null; }
    public function update(int $id,array $data,?int $ownerId=null): bool { return true; }
    public function delete(int $id,?int $ownerId=null): bool { return true; }
}
function expect(bool $condition, string $message): void { if (!$condition) throw new RuntimeException($message); }
$userDao = new FakeDAO();
$userService = new UserService($userDao);
$id = $userService->create(['full_name'=>'Test Student','email'=>'test@example.com','password'=>'secure-pass-123','role'=>'student']);
expect($id === 41, 'User service should return DAO identifier.');
expect(isset($userDao->created['password_hash']), 'User service should hash the password.');
expect(!isset($userDao->created['password']), 'Plain password must not reach DAO.');
expect(password_verify('secure-pass-123', $userDao->created['password_hash']), 'Stored password hash should verify.');
try { $userService->create(['full_name'=>'Bad','email'=>'not-an-email','password'=>'short']); throw new RuntimeException('Invalid email/password should fail validation.'); }
catch (InvalidArgumentException $e) { /* expected */ }
$courseDao = new FakeDAO();
$courseService = new CourseService($courseDao);
try { $courseService->create(['user_id'=>1,'course_name'=>'Web','course_code'=>'WEB','progress'=>120]); throw new RuntimeException('Progress above 100 should fail validation.'); }
catch (InvalidArgumentException $e) { /* expected */ }
echo "Service smoke tests passed.\n";
