<?php

declare(strict_types=1);

require_once __DIR__ . "/../../Database/Database.php";
require_once __DIR__ . "/../../DAO/RegistreerGebruikerDAO.php";
require_once __DIR__ . "/../../CustomExceptions/RegistrationCustomException.php";

use CustomExceptions\RegistrationCustomException;

class RegistreerController extends Database
{
    public function register(Gebruiker $user): void
    {
        $db = $this->connect();
        $userRepo = new RegistreerGebruikerDAO($db);
        $result = $userRepo->insertUser($user);

        if (!$result) {
            throw new RegistrationCustomException("User registration failed.");
        }

        header('Location: ../../../frontend/login-page/login.html');
        exit;
    }

}
