<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database for project';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            if (!extension_loaded('pdo')) {
                throw new \Exception('Could not find PDO extension.' . PHP_EOL);
            }

            if (!preg_match('/^[0-9a-zA-Z$_]+$/', ($db = $this->argument('name')))) {
                throw new \Exception('The database name must be correct.' . PHP_EOL);
            }

            try {
                $pdoConnection = new \PDO(
                    env('DB_CONNECTION') . ':host=' .
                    env('DB_HOST'),
                    env('DB_USERNAME'),
                    env('DB_PASSWORD')
                );

                $charset = env('DB_CHARSET');
                $collation = env('DB_COLLATION');

                $query = $pdoConnection->prepare("CREATE DATABASE IF NOT EXISTS $db CHARACTER SET $charset COLLATE $collation");

                if ($query->execute()) {
                    print("The database '$db' has been successfully created (or already exists)." . PHP_EOL);
                } else {
                    print("Failed to create database '$db'." . PHP_EOL);
                }
            } catch (\PDOException $e) {
                $message = __FILE__ . ' -> Error during database creation: ' . $e->getMessage() . PHP_EOL;
                \Log::error($message);
                die($message);
            }
        } catch (\Throwable $t) {
            $message = __FILE__ . ' -> ' . $t->getMessage() . PHP_EOL;
            \Log::error($message);
            die($message);
        }

        return 0;
    }
}
