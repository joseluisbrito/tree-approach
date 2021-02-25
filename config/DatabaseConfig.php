<?php

namespace Config;

// DatabaseConfig

class DatabaseConfig
{
    static $driver      = 'pdo_mysql';
    static $db_user     = 'tree_user';
    static $db_password = 'tree_password';
    static $db_name     = 'tree_db';
    static $host        = 'treehost';
    static $port        = '3306';
    
    public function __construct()
    {
        
    }
}