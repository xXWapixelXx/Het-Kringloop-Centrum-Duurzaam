<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Versie: 1.0
// Datum: 28-01-2026
// Beschrijving: Databaseverbinding via PDO. connect() is protected zodat alleen classes die extends Database doen (onze DAO’s) de verbinding kunnen gebruiken.

declare(strict_types=1);

class Database
{
    // Alleen aanroepbaar door deze class en door DAO’s (GebruikerDAO, ArtikelDAO, etc.) die extends Database doen
    protected function connect()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $port = 3306;
        $database = "duurzaam";

        try {
            $db = new PDO("mysql:host=$servername;port=$port;dbname=$database", $username, $password);
            return $db;
        } catch (PDOException $e) {
            die("Verbinding mislukt: " . $e->getMessage());
        }
    }
}