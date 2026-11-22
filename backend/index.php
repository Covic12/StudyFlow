<?php
declare(strict_types=1);

$autoload = __DIR__ . '/../vendor/autoload.php';
if (!is_file($autoload)) { http_response_code(500); header('Content-Type: application/json'); echo json_encode(['error'=>'dependency_missing','message'=>'Run composer install in the project root.']); exit; }
require $autoload;
require __DIR__ . '/routes/api.php';
Flight::route('GET /api/health', fn() => Flight::json(['status'=>'ok','application'=>'StudyFlow API','version'=>'1.0.0']));
Flight::start();
