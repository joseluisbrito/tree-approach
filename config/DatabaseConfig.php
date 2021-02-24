<?php

namespace Config;

// DatabaseConfig

class DatabaseConfig
{
    static $driver      = 'pdo_mysql';
    static $db_user     = 'tree_user';
    static $db_password = 'tree_password';
    static $db_name     = 'tree_db';
    static $host        = 'tree_host_db';
    static $port        = '3307';
    
    public function __construct()
    {
        
    }
}