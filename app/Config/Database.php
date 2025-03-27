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
            'hostname' => 'dpg-cvh55vqqgecs73cnme2g-a.oregon-postgres.render.com',
            'username' => 'banner_db_user',
            'password' => 'PuFrm86wjQJk0lpdkhB0xX55iDvcol8B',
            'database' => 'banner_db',
            'DBDriver' => 'Postgre',
            'DBPrefix' => '',
            'pConnect' => false,
            'DBDebug' => true,
            'charset' => 'utf8',
            'swapPre' => '',
            'encrypt' => [
                'ssl' => [
                    'verify_server_cert' => true,
                    'sslmode' => 'require',
                    'sslrootcert' => '/etc/ssl/certs/ca-certificates.crt'
                ],
            ],
            'port' => 5432,
            'connect_timeout' => 5
        ];

        // Use test database if in testing environment
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
