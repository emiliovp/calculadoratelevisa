<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

// Produccion
        // 'mysql' => [
        //     'driver' => 'mysql',
        //     'host' => '10.7.15.204',
        //     'port' => '3329',
        //     'database' => 'portal_pcb', // nombre de la database
        //     'username' => 'apps_admin_pcb', // usuario de la database
        //     'password' => 'eyJpdiI6Ik9BeUdiVkd2a1VqemZWUE1KM1ltckE9PSIsInZhbHVlIjoiUnZ1Z0s1aXYya2lzdGN4TzRzSmx6bWpIS3BYVzFFXC9KZTUzTndDMW9tU1k9IiwibWFjIjoiYzRmZWQyMjQ3OGMwMjkyZjFlZWFjNTQ3YjU4ZDcxZTIzNzVkMTE5NGVhNzM0YjliNGY0NmU5N2QyNWM4NDQ3OSJ9', // contraseña de la database
        //     'unix_socket' => env('DB_SOCKET', ''),
        //     // 'charset' => 'utf8mb4',
        //     // 'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'strict' => true,
        //     'engine' => null,
        //     'encriptado' => true,
        // ],

// QA
    /*'mysql' => [
        'driver' => 'mysql',
        'host' => '10.7.216.32',
        'port' => '3329',
        'database' => 'portal_pcb_fus', // nombre de la database
        'username' => 'consulta_pcbq', // usuario de la database
        'password' => 'eyJpdiI6ImtHcDBtcmpqWnNUak1kRkRXSm5FVFE9PSIsInZhbHVlIjoiS05objlweVUyQXNLNVEyVkRpY05Hdk1KT2U3VDI1anJUOGhJRjRTWW5rcz0iLCJtYWMiOiI3NmNjMDljMzhkMmIwNzhhMjI4OGY4NmE5NDg1OTc5ZmY4NDM5NTc0ZjBlZTU5NzRlOTE3Y2U0OGNhOWU0YzViIn0=', // password de la database
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_0900_ai_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => null,
        'encriptado' => true,
    ],*/
// Desarrollo
    'mysql' => [
        'driver' => 'mysql',
        'host' => '10.7.15.205',
        'port' => '3329',
        'database' => 'portal_pcb', // nombre de la database
        'username' => 'template', // usuario de la database
        'password' => 'eyJpdiI6IjJSQURIOW9aOXdRdmVsSm1vWVJmZnc9PSIsInZhbHVlIjoicE5vNU5tcXlUMGxHTitIcnlxQTFXSTRRQzk5NWVMMCtNZndOUmpnQ2pNTT0iLCJtYWMiOiI3MmNkMDQ5MjJjMDlmNzczODM4MGNmZGNiZDA1YWI4Y2IxMTBlZmUxNWQ0MTVlMjBjNjdlYzk1MmJjYWM1YjI0In0=', // contraseña de la database
        // 'charset' => 'latin1',
        // 'collation' => 'latin1_bin',
        'unix_socket' => env('DB_SOCKET', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_0900_ai_ci',
        'prefix' => '',
        'strict' => false,
        'engine' => null,
        'encriptado' => true,
    ],

// Desarrollo Local
        // 'mysql' => [
        //     'driver' => 'mysql',
        //     'host' => 'localhost',
        //     'port' => '3306',
        //     'database' => 'portal_pcb', // nombre de la database
        //     'username' => 'root', // usuario de la database
        //     'password' => 'eyJpdiI6ImQ2MFhGaFhtejNOcUpRSElmNFhGcFE9PSIsInZhbHVlIjoiRjc2NDFZSEUxOE55V3pBWDlMWU9Zdz09IiwibWFjIjoiZjA3Zjk1MzNiNmU3ZTI5M2M3YzQ5YTYzNjZiYjBhZDdkYzk2MGQ5ZDVmZDA3MjMyZWI0OTVlNmU5ZDJiMzcwZiJ9', // contraseña de la database
        //     // 'charset' => 'latin1',
        //     // 'collation' => 'latin1_bin',
        //     'unix_socket' => env('DB_SOCKET', ''),
        //     'charset' => 'utf8mb4',
        //     'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'strict' => false,
        //     'engine' => null,
        //     'encriptado' => true,
        // ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_DB', 0),
        ],

        'cache' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => env('REDIS_CACHE_DB', 1),
        ],

    ],

];
