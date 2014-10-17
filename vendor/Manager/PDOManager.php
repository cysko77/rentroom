<?php

namespace Manager;

class PDOManager
{

    protected static $instance = null;
    protected $pdo;

    private function __construct()
    {
        
    }

    private function __clone()
    {
        
    }

    public static function getInstance()
    {
        if (is_null(self::$instance))
        {
            self::$instance = new PDOManager;
        }
        return self::$instance;
    }

    public function getPdo()
    {
        require_once __DIR__ . '/../../app/Config.php';
        $config = new \Config;
        $connect = $config->getParametersConnect();
        try {
            $this->pdo = new \PDO('mysql:dbname=' . $connect['db'] . ';host=' . $connect['host'], $connect['user'], $connect['password'], array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION));
            $this->pdo->exec("SET CHARACTER SET utf8");
        } catch (\PDOException $e) {

            echo 'La tentive de connexion à la base de donnée a echoué:' . $e->getMessage();
            exit();
        }
        return $this->pdo;
    }

}

$pdo = PDOManager::getInstance()->getPdo();
?>