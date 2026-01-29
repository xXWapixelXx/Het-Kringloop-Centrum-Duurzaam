<?php

declare(strict_types=1);

require_once __DIR__ . "/../../Database/Database.php";
require_once __DIR__ . "/../../DAO/registreren/RegistrerenDAO.php";
require_once __DIR__ . "/../../CustomExceptions/RegistrationCustomException.php";

use CustomExceptions\RegistrationCustomException;

class RegistreerController extends Database
{
    // Registreren van een nieuwe klant
    public function register(Klant $user): void
    {
        // Maak verbinding met de database
        $db = $this->connect();
        // Maak een nieuw RegistrerenDAO object
        $userRepo = new RegistrerenDAO($db);
        // Probeer de gebruiker in te voegen
        $result = $userRepo->insertUser($user);

        if (!$result) {
            throw new RegistrationCustomException("User registration failed.");
        }

        // Redirect naar de login pagina na succesvolle registratie
        header('Location: ../../../frontend/login-page/login.php');
        exit;
    }
}
