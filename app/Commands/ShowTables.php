<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class ShowTables extends BaseCommand
{
    protected $group       = 'custom';
    protected $name        = 'db:show-tables';
    protected $description = 'List all tables in the database';

    public function run(array $params)
    {

        $db = Database::connect();
        
        $tables = $db->listTables();

        if (empty($tables)) {
            CLI::write('No tables found in the database.', 'yellow');
            return;
        }

        CLI::write('Tables in the database:', 'green');
        foreach ($tables as $table) {
            CLI::write('- ' . $table, 'white');
        }
    }
}
