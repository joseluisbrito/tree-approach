<?php

namespace Logic;

use Entities\Node;
use Entities\ParentNode;
use Controllers\TreeController;

class SetParentNode
{
    
    private $parentNode = null;

    public function __construct(int $node_id, int $parent_node_id)
    {
        $this->setParentNode($node_id, $parent_node_id);
    }
    
    /**
     * 
     * @param Node $node
     * @param Node $parentNode
     * @throws \Exception
     */
    private function setParentNode(int $node_id, int $parent_node_id)
    {
        try {
            
            $treeController = new TreeController();
            $node = $treeController->findNodeById($node_id);
            $parentNode = $treeController->findNodeById($parent_node_id);
            $this->parentNode = $treeController->setParentNode($node, $parentNode);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
    function getParentNode() {
        return $this->parentNode;
    }
    
}
