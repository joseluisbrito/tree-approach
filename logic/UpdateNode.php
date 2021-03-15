<?php

namespace Logic;

use Entities\Node;
use Controllers\TreeController;

class UpdateNode
{
    public function __construct()
    {
        
    }
    
    public function updateNode(int $node_id, string $new_name)
    {
        try {
            $treeController = new TreeController();
            $node = $treeController->findNodeById($node_id);
            $node->setName($new_name);
            $treeController->updateNode($node);
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}