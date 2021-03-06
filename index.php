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

// Set ParentNode:
if($_GET['uc']== 'setparentnode') {
    
    $api = new Rest("POST");
    
    $node_id = $api->getBody()->node_id;
    $parent_node_id = $api->getBody()->parent_node_id;

    if(isset($node_id) && is_numeric($node_id)
            && isset($parent_node_id) && is_numeric($parent_node_id)
      ) {

        try {

            $setParentNode = new \Logic\SetParentNode(
                    $node_id,
                    $parent_node_id
                    );
            
            $api->response(
                    "The node it is now related to its parent",
                    $api->HTTP_OK);
        } catch (Exception $ex) {
            $api->response($ex->getMessage(), $api->HTTP_BAD_REQUEST);
        }
        
    }
    else {
            $api->response("Bad request!", $api->HTTP_BAD_REQUEST);
    }
    
}

// Set ParentNode:
if($_GET['uc']== 'fetchjsontree') {
    
    $api = new Rest("GET");
    
    try {
        $tree = new \Logic\FetchTree();
        $api->response($tree->getTree(), $api->HTTP_OK);
    } catch (Exception $ex) {
        $api->response($ex->getMessage(), $api->HTTP_BAD_REQUEST);
    }
}

// Update Node:
if($_GET['uc']== 'updatenode') {
    
    $api = new Rest("POST");
    
    try {
        
        $node_id = $api->getBody()->node_id;
        $new_name = $api->getBody()->new_name;
        if(!isset($node_id) || !is_int($node_id)
                || !isset($new_name) || $new_name == ""
        ) {
            throw new Exception("Bad arguments!");
        }
        
        $updateNode = new \Logic\UpdateNode();
        $updateNode->updateNode($node_id, $new_name);
        $api->response("The node was updated!", $api->HTTP_OK);
        
    } catch (Exception $ex) {
        $api->response($ex->getMessage(), $api->HTTP_BAD_REQUEST);
    }
}
