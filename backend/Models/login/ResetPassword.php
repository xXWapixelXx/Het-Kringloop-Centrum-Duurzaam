<?php

declare(strict_types=1);

use CustomExceptions\ResetPasswordCustomException;

require_once __DIR__ . "/../../Controllers/login/UpdatePasswordController.php";

// Krijg de gebruikersnaam en het nieuwe wachtwoord van het formulier
$gebruikersnaam = $_POST['gebruikersnaam'] ?? null;
$newPassword = $_POST['new_password'] ?? null;

if (!$gebruikersnaam || !$newPassword) {
    die("Username and new password are required.");
}

// Maak een instantie van de controller
$controller = new UpdatePasswordController();

// Probeer het wachtwoord te resetten
try {
    $success = $controller->resetPassword($gebruikersnaam, $newPassword);
    if ($success) {
        header('Location: ../../../frontend/login-page/login.php');
    } else {
        echo "Password reset failed. Make sure the username exists.";
    }
    // Afhandelen van eventuele uitzonderingen
} catch (ResetPasswordCustomException $e) {
    echo $e->getMessage();
}
