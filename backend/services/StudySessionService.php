<?php
declare(strict_types=1);
require_once __DIR__ . '/BaseService.php';
final class StudySessionService extends BaseService
{
    protected function requiredFields(): array { return ['user_id', 'title', 'start_at', 'end_at']; }
    protected function fieldRules(): array { return ['title' => 180]; }

    protected function prepare(array $data): array
    {
        // Duration is derived from the supplied time interval when the client omits it.
        if (isset($data['start_at'], $data['end_at']) && !isset($data['duration_minutes'])) {
            $minutes = (int)ceil((strtotime((string)$data['end_at']) - strtotime((string)$data['start_at'])) / 60);
            if ($minutes > 0) $data['duration_minutes'] = $minutes;
        }
        return $data;
    }
}
