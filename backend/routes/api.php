<?php
declare(strict_types=1);
require_once __DIR__ . '/../services/ServiceFactory.php';
require_once __DIR__ . '/../services/OpenLibraryService.php';
require_once __DIR__ . '/../services/ApiLogger.php';

function apiBody(): array
{
    $raw = file_get_contents('php://input');
    if ($raw === '' || $raw === false) return [];
    $data = json_decode($raw, true);
    if (!is_array($data)) throw new InvalidArgumentException('Request body must be a valid JSON object.');
    return $data;
}
function apiRespond(callable $action): void
{
    try { $action(); }
    catch (InvalidArgumentException $e) {
        $details = json_decode($e->getMessage(), true);
        Flight::json(['error' => 'validation_error', 'message' => 'The request contains invalid data.', 'details' => is_array($details) ? $details : null], 400);
    }
    catch (PDOException $e) {
        ApiLogger::error('Database exception: ' . $e->getMessage());
        Flight::json(['error' => 'database_error', 'message' => 'The request could not be completed.'], 500);
    }
    catch (Throwable $e) {
        ApiLogger::error('Unhandled API exception: ' . $e->getMessage());
        Flight::json(['error' => 'server_error', 'message' => 'An unexpected server error occurred.'], 500);
    }
}

$resources = [
    'users' => ['service' => fn() => ServiceFactory::users(), 'key' => 'user_id'],
    'courses' => ['service' => fn() => ServiceFactory::courses(), 'key' => 'course_id'],
    'assignments' => ['service' => fn() => ServiceFactory::assignments(), 'key' => 'assignment_id'],
    'study-sessions' => ['service' => fn() => ServiceFactory::studySessions(), 'key' => 'session_id'],
    'notes' => ['service' => fn() => ServiceFactory::notes(), 'key' => 'note_id'],
    'categories' => ['service' => fn() => ServiceFactory::categories(), 'key' => 'category_id'],
];
foreach ($resources as $path => $definition) {
    $serviceFactory = $definition['service'];
    $key = $definition['key'];
    Flight::route('GET /api/v1/' . $path, function () use ($serviceFactory) { apiRespond(function () use ($serviceFactory) { Flight::json(['data' => $serviceFactory()->all()]); }); });
    Flight::route('GET /api/v1/' . $path . '/@id:[0-9]+', function ($id) use ($serviceFactory) { apiRespond(function () use ($serviceFactory, $id) { $row=$serviceFactory()->get((int)$id); if (!$row) { Flight::json(['error'=>'not_found','message'=>'Resource not found.'],404); return; } Flight::json(['data'=>$row],200); }); });
    Flight::route('POST /api/v1/' . $path, function () use ($serviceFactory) { apiRespond(function () use ($serviceFactory) { $id=$serviceFactory()->create(apiBody()); Flight::json(['message'=>'Resource created.','id'=>$id],201); }); });
    foreach (['PUT','PATCH'] as $method) Flight::route($method . ' /api/v1/' . $path . '/@id:[0-9]+', function ($id) use ($serviceFactory) { apiRespond(function () use ($serviceFactory,$id) { if (!$serviceFactory()->update((int)$id,apiBody())) { Flight::json(['error'=>'not_found','message'=>'Resource not found or unchanged.'],404); return; } Flight::json(['message'=>'Resource updated.'],200); }); });
    Flight::route('DELETE /api/v1/' . $path . '/@id:[0-9]+', function ($id) use ($serviceFactory) { apiRespond(function () use ($serviceFactory,$id) { if (!$serviceFactory()->delete((int)$id)) { Flight::json(['error'=>'not_found','message'=>'Resource not found.'],404); return; } Flight::json(['message'=>'Resource deleted.'],200); }); });
}

Flight::route('GET /api/v1/resources/search', function () {
    apiRespond(function () {
        $configFile = __DIR__ . '/../config/config.php';
        $config = is_file($configFile) ? require $configFile : [];
        $base = $config['external_api']['open_library_base_url'] ?? 'https://openlibrary.org';
        $query = (string)($_GET['q'] ?? '');
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        try { $result=(new OpenLibraryService($base))->search($query,$limit); Flight::json(['data'=>$result],200); }
        catch (InvalidArgumentException $e) { Flight::json(['error'=>'validation_error','message'=>$e->getMessage()],400); }
        catch (Throwable $e) { ApiLogger::error('Open Library failure: '.$e->getMessage()); Flight::json(['error'=>'external_api_unavailable','message'=>'Learning resources are temporarily unavailable. Please try again later.'],502); }
    });
});
