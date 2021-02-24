<?php

// src/Node.php
namespace Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="node")
 */
 class Node implements \JsonSerializable
 {

   /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type="integer")
    * @var int
    */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
     protected $name;

     public function __construct()
     {
     }

     public function getName()
     {
       return $this->name;
     }

     public function setName($name)
     {
       $this->name = $name;
     }

     public function getId()
     {
       return $this->id;
     }

     public function jsonSerialize()
     {
       return (object) get_object_vars($this);
     }

 }