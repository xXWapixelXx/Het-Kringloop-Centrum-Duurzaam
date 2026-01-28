<?php

declare(strict_types=1);

require_once __DIR__ . "/../../Models/Gebruiker.php";
require_once __DIR__ . "/../../Presentation/registreer/RegistreerController.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user = new Gebruiker(
        0,
        $_POST['gebruikersnaam'],
        $_POST['wachtwoord'],
        0,
    );

    $controller = new RegistreerController();
    $controller->register($user);
}
