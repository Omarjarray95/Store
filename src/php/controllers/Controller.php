<?php

namespace App\Controller;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Controller
{
    protected $twig;
    protected $em;

    public function __construct()
    {
        //require_once "";

        $isDevMode = true;
        $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__ . "/src/php/models"), $isDevMode);
        $conn = array(
            'dbname' => 'bookstore',
            'user' => 'root',
            'password' => '',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        );
        $entityManager = EntityManager::create($conn, $config);
        $this->em = $entityManager;

        $loader = new Twig_Loader_Filesystem(["src/html", "src/"]);
        $this->twig = new Twig_Environment($loader);
    }
}