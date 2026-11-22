<?php
// Copy this file to config.php and replace local values. Never commit config.php.
return [
    'database' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'studyflow',
        'username' => 'studyflow_user',
        'password' => 'change_me_locally',
        'charset' => 'utf8mb4',
    ],
    'jwt' => ['secret' => 'replace_with_a_long_random_secret'],
    'external_api' => ['open_library_base_url' => 'https://openlibrary.org'],
];
