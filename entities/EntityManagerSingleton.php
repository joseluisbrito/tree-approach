<?php

namespace Entities;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Config\DatabaseConfig;

class EntityManagerSingleton
{
    private static $instances = [];
    private $em = null;
    private $conn = null;
    private $config = null;
    
    protected function __construct() {
        $this->setEntityManager();
    }
    
    /**
     * Cloning and serialization are note permitted for singletons.
     */
    protected function __clone() {
        
    }
    public function __wakeup() {
        throw new Exception("Cannot unserialize singleton");
    }
    
        /**
     * The method to get a Singleton instance of CtrlSystem
     */
    public static function getInstance() {
        $subclass = static::class;
        if(!isset(self::$instance[$subclass])) {
            self::$instances[$subclass] = new static();
        }
        return self::$instances[$subclass];
    }
    
    
    private static $isDevMode = true;
    private static $proxyDir = null;
    private static $cache = null;
    private static $useSimpleAnnotationReader = false;
               
    private function setEntityManager() {
        
        $this->conn = array(
            'driver' => DatabaseConfig::$driver,
            'user' => DatabaseConfig::$db_user,
            'password' => DatabaseConfig::$db_password,
            'dbname' => DatabaseConfig::$db_name,
            'host' => DatabaseConfig::$host,
            'port' => DatabaseConfig::$port,
            'charset'  => 'utf8',
            'driverOptions' => array(
                1002 => 'SET NAMES utf8'
            )
        );
        
        $this->config = Setup::createAnnotationMetadataConfiguration(
            array(__DIR__),
            self::$isDevMode,
            self::$proxyDir,
            self::$cache,
            self::$useSimpleAnnotationReader
            );
        $this->em = EntityManager::create($this->conn, $this->config);
    }
    
    public function getEM() {
        $this->setEntityManager();
        return $this->em;
    }
    
}