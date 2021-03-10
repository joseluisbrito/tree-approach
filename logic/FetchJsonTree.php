<?php

namespace Logic;

use \Controllers\TreeController;

class FetchTree
{
    private $tree = null;
    
    public function __construct()
    {
        $this->fetchTree();
    }
    
    private function fetchTree()
    {
        $treeController = new TreeController();
        $this->treee = $treeController->fetchTree();
    }
    
    function getTree()
    {
        return $this->tree;
    }
    
}
