<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor login functionaliteit

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../DAO/GebruikerDAO.php';

class LoginController
{
    public $error = "";

    // verwerk login
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $gebruikersnaam = $_POST['gebruikersnaam'] ?? '';
        $wachtwoord = $_POST['wachtwoord'] ?? '';

        if (empty($gebruikersnaam) || empty($wachtwoord)) {
            $this->error = "Vul alle velden in.";
            return;
        }

        $dao = new GebruikerDAO();
        $gebruiker = $dao->getByGebruikersnaam($gebruikersnaam);

        if ($gebruiker && password_verify($wachtwoord, $gebruiker->wachtwoord)) {
            $_SESSION['gebruiker_id'] = $gebruiker->id;
            $_SESSION['gebruikersnaam'] = $gebruiker->gebruikersnaam;
            $_SESSION['rol_id'] = $gebruiker->rol_id;

            header('Location: ../../frontend/views/dashboard.php');
            exit;
        } else {
            $this->error = "Ongeldige gebruikersnaam of wachtwoord.";
        }
    }
}

// run controller
$controller = new LoginController();
$controller->handleLogin();
$error = $controller->error;

// laad view
require_once __DIR__ . '/../../frontend/templates/login.html';
