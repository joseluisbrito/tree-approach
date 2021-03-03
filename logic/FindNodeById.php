<?php

namespace Logic;

use Entities\Node;
use Controllers\TreeController;

class FindNodeById
{

    public function __construct()
    {
    }
    
    /**
     * 
     * @param int $node_id
     * @return Node
     * @throws \Exception
     */
    public function findById(int $node_id): Node
    {
        try {
            
            $treeController = new TreeController();
            return $treeController->findNodeById($node_id);
            
        } catch (\Exception $ex) {
            throw $ex;
        }
    }
    
}
