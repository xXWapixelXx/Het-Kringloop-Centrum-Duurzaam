<?php

declare(strict_types=1);

class Database
{
    protected function connect()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $port = 3307;
        $database = "duurzaam";

        try {
            $db = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
            return $db;
        } catch (PDOException $e) {
            die("Connection faile: " . $e->getMessage());
        }
    }
}