<?php

// src/parent.php
namespace Entities;

use Entities\Node;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parent_nodes")
 */
class ParentNode
{

  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @var int
   */
   protected $id;

  /**
   * @ORM\ManyToOne(targetEntity="node", inversedBy="id")
   * @var Node node
   */
   protected $node;

   /**
   * @ORM\ManyToOne(targetEntity="node", inversedBy="id")
    * @var Node ParentNode
    */
    protected $parentNode;

    public function setNode(Node $node)
    {
      $this->node = $node;
    }

    public function setParentNode(Node $parentNode)
    {
      $this->parentNode = $parentNode;
    }

    public function getNode()
    {
      return $this->node;
    }

    public function getParentNode()
    {
      return $this->parentNode;
    }

    public function getId()
    {
      return $this->id;
    }
}
