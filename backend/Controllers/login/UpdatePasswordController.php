<?php

declare(strict_types=1);

require_once __DIR__ . "/../../Database/Database.php";
require_once __DIR__ . "/../../DAO/login/UpdatePasswordDAO.php";

class UpdatePasswordController extends Database
{
    // Reset het wachtwoord van een gebruiker
    public function resetPassword(string $gebruikersnaam, string $newPassword): bool
    {
        $pdo = $this->connect();
        // Maak een instantie van de UpdatePasswordDAO
        $dbal = new UpdatePasswordDAO($pdo);
        // Roep de updatePassword-methode aan
        return $dbal->updatePassword($gebruikersnaam, $newPassword);
    }
}
