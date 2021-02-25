<?php

namespace Logic;

use Entities\Node;
use Controllers\TreeController;

class CreateNode
{
    private $node = null;
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
            $treeController->createNode($node);
            $this->node = $node;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
    
    function getNode(): Node {
        return $this->node;
    }
    
}
