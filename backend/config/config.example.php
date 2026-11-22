<?php
// Copy to config.php locally. Never commit config.php or real credentials.
return [
    'database' => [
        'host' => '127.0.0.1',
        'name' => 'studyflow',
        'username' => 'your_database_user',
        'password' => 'your_database_password',
        'charset' => 'utf8mb4',
    ],
    'jwt' => ['secret' => 'replace_with_a_long_random_secret'],
    'external_api' => ['open_library_base_url' => 'https://openlibrary.org'],
];
