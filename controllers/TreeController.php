<?php

namespace Controllers;

use Entities\Node;
use Entities\ParentNode;
use Entities\EntityManagerSingleton;

class TreeController
{

  // Node entity string (for Doctrine):
  protected $nodeEntityName = "\Entities\Node";
  // ParentNode entity string (for Doctrine):
  protected $parentNodeEntityName = "\Entities\ParentNode";

  // EntityManager instance to work with ORM Doctrine:
  protected $entityManager;

  // To keep all nodes from database in memory:
  private $nodes_Array = [];

  // To keep all parents from database in memory:
  private $parents_Array = [];

  // To keep roots Nodes in memory:
  private $roots_Array = [];

  // To keep entire tree in memory:
  private $tree = [];

  public function __construct()
  {
    // Get EntityManager instance:
    $ems = EntityManagerSingleton::getInstance();
    $this->entityManager = $ems->getEM();
  }

  // Save a Node into database
  public function createNode(Node $node): Node
  {
    $this->entityManager->persist($node);
    $this->entityManager->flush();
    return $node;
  }

  // Create parentNode relation into database:
  public function setParentNode(Node $node, Node $parentNode): ParentNode
  {
    if(!isset($node) || !isset($parentNode))
      throw new \Exception("Node and ParentNode need to be valid ", 1);

    if($node->getId() == $parentNode->getId())
      throw new \Exception("Node cannot be itself parent!");

    if($this->haveParent($node))
      throw new \Exception("This node already have a parent!");

    // Prevent circular references
    $this->findAllNodes();
    $this->findAllParent();
    $this->findRootsFromArray();
    
    if($this->searchChildNode($node, $parentNode))
      throw new \Exception("Relate those nodes result in a circular reference");

    $newParentNode = new ParentNode();
    $newParentNode->setParentNode($parentNode);
    $newParentNode->setNode($node);
    $this->entityManager->persist($newParentNode);
    $this->entityManager->flush();
    return $newParentNode;
  }

  // Search and return Node from database
  public function findNodeById(int $id): Node
  {
    $node = $this->entityManager->find($this->nodeEntityName, $id);
    if(!isset($node))
      throw new \Exception("Node does not exist");
    return $node;
  }

  // Search all Nodes and put them in array
  private function findAllNodes()
  {
    $repository = $this->entityManager->getRepository($this->nodeEntityName);
    $this->nodes_Array = $repository->findAll();
  }

  // Search all ParentNode instances and put them in array
  private function findAllParent()
  {
    $repository = $this->entityManager->getRepository($this->parentNodeEntityName);
    $this->parents_Array = $repository->findAll();
  }

  // Return an array containing the parent of the node received
  private function findParent(Node $node)
  {
    $query= $this->entityManager
    ->createQuery("SELECT pn FROM $this->parentNodeEntityName as pn "
    . ' WHERE pn.node = ?1')
    ->setParameter(1, $node);
    return $query->getResult();
  }

  // Check if Node have parent relationship
  private function haveParent(Node $node): Bool
  {
    // Check if some node have some parent:
    if(count($this->findParent($node))) {
      return true; // have parent
    }
    return false; // haven´t parent
  }

  // Return an Array with children of a Node
  public function findChildren(Node $parentNode)
  {
    $query = $this->entityManager
    ->createQuery("SELECT pn FROM $this->parentNodeEntityName n, "
    ." $this->parentNodeEntityName pn "
    ." WHERE pn.parentNode = ?1 ORDER BY pn.node")
    ->setParameter(1, $parentNode);
    $parentNodes =  $query->getResult();
    $children = [];
    foreach ($parentNodes as $pn) {
      array_push($children, $pn->getNode());
    }
    return $children;
  }

  // Return all nodes separately
  public function getAllNodes()
  {
    if(count($this->nodes_Array)==0)
      $this->findAllNodes();
    return $this->nodes_Array;
  }

  // Update Node name into database
  public function updateNode(Node $node): Node
  {
    $n = $this->entityManager->find($this->nodeEntityName, $node->getId());
    $n->setName($node->getName());
    $this->entityManager->flush();
    return $n;
  }

  // Chek if node exist, has children and then call deleteNode
  public function removeNode(Node $node)
  {
    // Node without id is not removable
    if(!is_numeric($node->getId()))
      throw new \Exception("Can´t remove this node");

    // Remove node having children is not permited
    if(count($this->findChildren($node)) > 0)
      throw new \Exception("Remove node with children is not permitted", 1);

    $this->deleteNode($node);
  }

  // Remove a Node from database and its parent relationship
  private function deleteNode(Node $node)
  {
    $parentNode = $this->findParent($node);
    $this->entityManager->remove($parentNode[0]);
    $this->entityManager->remove($node);
    $this->entityManager->flush();
  }

  // Return entire tree in array format
  public function fetchTree()
  {
    $this->findAllNodes();
    $this->findAllParent();
    $this->findRootsFromArray();
    foreach ($this->roots_Array as $r) {
      array_push($this->tree,$this->retrieveChildrenFromArray($r));
    }
    return $this->tree;
  }

  private function findRootsFromArray()
  {
    $nodesWithParent = [];
    foreach ($this->parents_Array as $pn) {
        array_push($nodesWithParent, $pn->getNode());
    }
    $this->roots_Array = array_udiff($this->nodes_Array, $nodesWithParent,
      function ($obj_a, $obj_b) {
        return $obj_a->getId() - $obj_b->getId();
      }
    );

  }

  // Retrieve children from in-memory array, recursively
  private function retrieveChildrenFromArray(Node $parent)
  {
    $children = [];
    foreach($this->parents_Array as $pn)
    {
      if($pn->getParentNode() == $parent)
        if($this->hasChildrenFromArray($pn->getNode())) {
          array_push(
            $children,
            $this->retrieveChildrenFromArray($pn->getNode()));
        }
        else {
          array_push($children, array("node"=>$pn->getNode()));
        }
    }
    $result["node"] = $parent;
    $result["children"] = $children;
    return $result;
  }

  // Search if node is child or ancestor of parent
  private function searchChildNode(Node $node, Node $parent)
  {
    // Root nodes cant be pointed to a parent
    if($this->isRootNode($node))
        return true;
    // Search parent node of node received:
    if($node->getId() === $parent->getId())
        return true;
    $pn = $this->findParent($parent);
    
    if(count($pn) > 0)
        $this->searchChildNode($node, $pn[0]->getParentNode());
    return false;
  }

  // Find and return children from array
  private function findChildrenFromArray(Node $parent)
  {
    $children = [];
    foreach ($this->parents_Array as $pn) {
      if($pn->getParentNode() == $parent)
        array_push($children, $pn->getNode());
    }
    return $children;
  }


  // Check if the current node have some children
  private function hasChildrenFromArray(Node $parent): Bool
  {
    foreach($this->parents_Array as $pn)
    {
      if($pn->getParentNode() == $parent)
        return true;
    }
    return false;
  }
  
  // Recive node and check if it is a rootNode
  private function isRootNode(Node $node): Bool
  {
      foreach($this->roots_Array as $r)
      {
        if($r === $node && $this->hasChildrenFromArray($r))
            return true;
      }
      return false;
  }
}