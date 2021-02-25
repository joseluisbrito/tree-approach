<?php

namespace Logic;

use Entities\Node;
use Controllers\TreeController;

class CreateNode
{
    
    public function __construct($name)
    {
        $this->createNode($name);
    }
    
    private function createNode($name)
    {
        try {
            $node = new Node();
            $node->setName($name);
            $treeController = new TreeController();
            return $treeController->createNode($node);            
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
}
