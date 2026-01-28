<?php

declare(strict_types=1);

class Database
{
    // Heb een proctected functie connect met variabelen aangemaakt voor de connectie met de database
    protected function connect()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $port = 3307;
        $database = "duurzaam";

    // Heb een try catch aangemaakt zodat als de connectie faalt dat er een error word weegegeven
        try {
            $db = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
            return $db;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}