<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Versie: 1.0
// Datum: 28-01-2026
// Beschrijving: Databaseverbinding via PDO voor project duurzaam

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
            die("Verbinding mislukt: " . $e->getMessage());
        }
    }
}