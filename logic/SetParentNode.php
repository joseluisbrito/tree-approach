<?php

namespace Logic;

use Entities\Node;
use Entities\ParentNode;
use Controllers\TreeController;

class SetParentNode
{
    
    private $parentNode = null;

    public function __construct(Node $node, Node $parentNode)
    {
        $this->setParentNode($node, $parentNode);
    }
    
    /**
     * 
     * @param Node $node
     * @param Node $parentNode
     * @throws \Exception
     */
    private function setParentNode(Node $node, Node $parentNode): ParentNode
    {
        try {
            
            $treeController = new TreeController();
            $this->parentNode = $treeController->setParentNode($node, $parentNode);
            
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    function getParentNode() {
        return $this->parentNode;
    }
    
}
