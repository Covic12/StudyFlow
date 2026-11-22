<?php
declare(strict_types=1);

/** Server-side Open Library adapter. External failures are converted to safe domain errors. */
final class OpenLibraryService
{
    public function __construct(private string $baseUrl = 'https://openlibrary.org', private int $timeout = 6) {}

    public function search(string $query, int $limit = 10): array
    {
        $query = trim($query);
        if ($query === '' || (function_exists('mb_strlen') ? mb_strlen($query) : strlen($query)) > 120) throw new InvalidArgumentException('Search query must contain between 1 and 120 characters.');
        $limit = max(1, min($limit, 20));
        $url = rtrim($this->baseUrl, '/') . '/search.json?q=' . rawurlencode($query) . '&limit=' . $limit;
        $context = stream_context_create(['http' => ['timeout' => $this->timeout, 'ignore_errors' => true, 'header' => "Accept: application/json\r\nUser-Agent: StudyFlow/1.0\r\n"]]);
        $body = @file_get_contents($url, false, $context);
        if ($body === false) throw new RuntimeException('The learning resources provider is temporarily unavailable.');
        $status = 0;
        foreach (($http_response_header ?? []) as $header) if (preg_match('/^HTTP\/\S+\s+(\d{3})/', $header, $m)) $status = (int)$m[1];
        if ($status < 200 || $status >= 300) throw new RuntimeException('The learning resources provider returned an error.');
        $decoded = json_decode($body, true);
        if (!is_array($decoded) || !isset($decoded['docs']) || !is_array($decoded['docs'])) throw new RuntimeException('The learning resources provider returned an invalid response.');
        $books = [];
        foreach ($decoded['docs'] as $book) {
            $key = $book['key'] ?? null;
            if (!$key) continue;
            $books[] = [
                'title' => $book['title'] ?? 'Untitled',
                'authors' => array_slice($book['author_name'] ?? [], 0, 3),
                'first_publish_year' => $book['first_publish_year'] ?? null,
                'edition_count' => $book['edition_count'] ?? 0,
                'cover_url' => !empty($book['cover_i']) ? 'https://covers.openlibrary.org/b/id/' . (int)$book['cover_i'] . '-M.jpg' : null,
                'open_library_url' => 'https://openlibrary.org' . $key,
            ];
        }
        return ['query' => $query, 'count' => count($books), 'results' => $books];
    }
}
