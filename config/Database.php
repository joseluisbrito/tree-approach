<?php

namespace Config;

use Config\DatabaseConfig as DB;

class Database
{
    protected $conn = null;
    protected $sqlFile = null;
    protected $sqlFileName = null;
    
    public function __construct()
    {
    }
    
    /**
     * Check if tables exists or not
     * If not, create them using a sql file
     */
    public function checkTables()
    {
        
    }
    
    /**
     * Make a conection
     */
    private function connect()
    {
        try {
            $this->conn = new \PDO(
                    'mysql:host='.DB::$host.';port='.DB::$port.';dbname='
                    .DB::$db_name, DB::$db_user,
                    DB::$db_password);
        } catch (PDOException $e) {
            throw new Exception("DB conection error");
        }
    }
    
    /**
     * Load a fixture file from Fixtures folder
     */
    private function loadFixtureFile()
    {
        $this->sqlFile = file_get_contents(__DIR__."/".$this->sqlFileName);
    }
    
    /**
     * Load a fixture file to the database
     * @param String $fixtureFileName The file name to be loaded
     */
    public function load($fixtureFileName)
    {
        $this->connect();
        $this->sqlFileName = $fixtureFileName;
        $this->loadFixtureFile();
        $this->conn->exec($this->sqlFile);
    }
}