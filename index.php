<?php

require_once 'vendor/autoload.php';

use Entities\Node;
use Rest\Rest;

// First, check if the database has correct tables created
// You can create new sql file using Doctrine schema-tool !
$db = new Config\Database();
$db->load('tree_db_empty.sql');

// USE CASES:

// Create Node:
if($_GET['uc']== 'createnode') {
    
    $api = new Rest("POST");
    
    $name = $api->getBody()->name;

    if(isset($name) && $name !== "") {

        try {
            $createNode = new \Logic\CreateNode($name);

            $api->response(
                    "The node was created with id: "
                    .$createNode->getNode()->getId(),
                    $api->HTTP_OK);
        } catch (Exception $ex) {
            $api->response($ex->getMessage(), $api->HTTP_BAD_REQUEST);
        }
        
    }
    else {
            $api->response("Bad request!", $api->HTTP_BAD_REQUEST);
    }
}