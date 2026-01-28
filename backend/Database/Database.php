<?php

declare(strict_types=1);

class Database
{
    // Made protected function connect with variables that is needed for the connection with the database
    protected function connect()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $port = 3307;
        $database = "duurzaam";

    // Made a try catch so when the connection fails it will show an error
        try {
            $db = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
            return $db;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}