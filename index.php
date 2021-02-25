<?php

require_once 'vendor/autoload.php';

// First, check if the database has correct tables created
// You can create new sql file using Doctrine schema-tool !
$db = new Config\Database();
$db->load('tree_db_empty.sql');

