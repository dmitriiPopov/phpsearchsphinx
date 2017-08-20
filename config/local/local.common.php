<?php
/**
 * local common configuration
 */
return [
    'components' => [
        'db' => [
            'dsn'      => 'mysql:host=localhost;dbname=deshevshe',
            'username' => 'root',
            'password' => '2824562',
        ],
        'search' => [
            'finders' => [
                'products' => [
                    'host'      => 'localhost',
                    'port'      => 9312,
                    'indexName' => 'deshevshe',
                ],
            ],
        ],
    ],
];