<?php

namespace Config;

use CodeIgniter\Database\Config;

class Database extends Config
{
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;
    public string $defaultGroup = 'default';

    public array $default;

    public function __construct()
    {
        parent::__construct();

        $this->default = [
            'DSN' => '',
            'hostname' => getenv('database.default.hostname') ?: 'localhost',
            'username' => getenv('database.default.username') ?: 'root',
            'password' => getenv('database.default.password') ?: '',
            'database' => getenv('database.default.database') ?: '',
            'DBDriver' => getenv('database.default.DBDriver') ?: 'Postgre',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => true,
            'charset' => 'utf8',
            'DBCollat' => 'utf8_general_ci',
            'swapPre' => '',
            'encrypt' => [  // Changed from false to array
                'ssl' => [
                    'verify_server_cert' => (ENVIRONMENT === 'production'),
                    // For Render.com production:
                    'ca' => '/etc/ssl/certs/ca-certificates.crt'
                ],
            ],
            'compress' => false,
            'strictOn' => false,
            'failover' => [],
            'port' => getenv('database.default.port') ? (int) getenv('database.default.port') : 3306,
        ];

        // Use test database if in testing environment
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
