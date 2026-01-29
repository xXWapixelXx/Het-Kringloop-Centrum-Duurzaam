<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Versie: 1.0
// Datum: 29-01-2026
// Beschrijving: Wachtwoord reset. Extends Database voor connect(); roept UpdatePasswordDAO aan om wachtwoord in DB te updaten (gehashed).

declare(strict_types=1);

require_once __DIR__ . "/../../Database/Database.php";
require_once __DIR__ . "/../../DAO/login/UpdatePasswordDAO.php";

class UpdatePasswordController extends Database
{
    // connect via parent; DAO doet prepare + bindValue + execute om wachtwoord (hash) in DB te zetten
    public function resetPassword(string $gebruikersnaam, string $newPassword): bool
    {
        $pdo = $this->connect();
        // Maak een instantie van de UpdatePasswordDAO
        $dbal = new UpdatePasswordDAO($pdo);
        // Roep de updatePassword-methode aan
        return $dbal->updatePassword($gebruikersnaam, $newPassword);
    }
}
