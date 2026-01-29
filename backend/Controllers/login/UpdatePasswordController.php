<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Versie: 1.0
// Datum: 29-01-2026
// Beschrijving: Wachtwoord reset. Extends Database voor connect(); roept UpdatePasswordDAO aan om wachtwoord in DB te updaten (gehashed).

declare(strict_types=1);

// Database.php: nodig omdat we extends Database doen en straks $this->connect() aanroepen
require_once __DIR__ . "/../../Database/Database.php";
// UpdatePasswordDAO: die doet het echte werk (password_hash + UPDATE-query met prepared statement)
require_once __DIR__ . "/../../DAO/login/UpdatePasswordDAO.php";

// extends Database = we erven connect() van de parent; daardoor kunnen we $this->connect() gebruiken
class UpdatePasswordController extends Database
{
    // Stap 1: verbinding maken. Stap 2: verbinding aan DAO geven. Stap 3: DAO laat hashen + UPDATE doen. Return true/false.
    public function resetPassword(string $gebruikersnaam, string $newPassword): bool
    {
        // $pdo = de databaseverbinding (PDO-object). connect() komt uit de parent class Database
        $pdo = $this->connect();
        // UpdatePasswordDAO heeft geen extends Database, dus geen eigen connect(); we geven $pdo mee
        $dbal = new UpdatePasswordDAO($pdo);
        // DAO hasht het wachtwoord en doet UPDATE gebruiker SET wachtwoord = ... WHERE gebruikersnaam = ...
        return $dbal->updatePassword($gebruikersnaam, $newPassword);
    }
}
