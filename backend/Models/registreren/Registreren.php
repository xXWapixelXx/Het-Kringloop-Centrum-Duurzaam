<?php

declare(strict_types=1);

require_once __DIR__ . "/../../Models/Klant.php";
require_once __DIR__ . "/../../Controllers/registreren/RegistrerenController.php";

// Check of de request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Haal en sanitizeer de input gegevens
    $naam = trim((string) ($_POST['naam'] ?? ''));
    $adres = trim((string) ($_POST['adres'] ?? ''));
    $plaats = trim((string) ($_POST['plaats'] ?? ''));
    $telefoon = trim((string) ($_POST['telefoon'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));

    // Maak een nieuw Klant object aan
    $user = new Klant(
        0,
        $naam,
        $adres,
        $plaats,
        $telefoon,
        $email
    );

    $controller = new RegistreerController();
    $controller->register($user);
}
