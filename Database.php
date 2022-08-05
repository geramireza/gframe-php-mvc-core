<?php

namespace gframe\phpmvc;

use PDO;

class Database
{
//    public PDO $pdo;
    public function __construct($dbConfig)
    {
        $dns = $dbConfig['dns'];
        $username = $dbConfig['username'];
        $password = $dbConfig['password'];

        $this->pdo = new PDO($dns,$username,$password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
}