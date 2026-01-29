<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Versie: 1.0
// Datum: 28-01-2026
// Beschrijving: Controller voor login. Onderaan: constructor aanroepen, handleLogin() uitvoeren, daarna login-pagina laden.

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/GebruikerDAO.php';

class LoginController
{
    public $error = "";

    // Alleen bij POST: valideer velden, haal gebruiker op via DAO, controleer wachtwoord met password_verify, vul sessie, redirect naar dashboard
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // Haal gebruikersnaam en wachtwoord uit formulier; trim gebruikersnaam tegen spaties
        $gebruikersnaam = isset($_POST['gebruikersnaam']) ? trim($_POST['gebruikersnaam']) : '';
        $wachtwoord = isset($_POST['wachtwoord']) ? $_POST['wachtwoord'] : '';

        if ($gebruikersnaam === '' || $wachtwoord === '') {
            $this->error = "Vul alle velden in.";
            return;
        }

        // Validatie gebruikersnaam: lengte 3–20, alleen letters/cijfers/underscore (geen speciale tekens)
        $lengte = strlen($gebruikersnaam);
        if ($lengte < 3 || $lengte > 20) {
            $this->error = "Gebruikersnaam moet tussen 3 en 20 tekens zijn.";
            return;
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $gebruikersnaam)) {
            $this->error = "Gebruikersnaam mag alleen letters, cijfers en een underscore bevatten.";
            return;
        }

        // DAO haalt gebruiker op uit database (prepared statement, veilig tegen SQL-injectie)
        $dao = new GebruikerDAO();
        $gebruiker = $dao->getByGebruikersnaam($gebruikersnaam);

        if (!$gebruiker) {
            $this->error = "Ongeldige gebruikersnaam of wachtwoord.";
            return;
        }

        // Geblokkeerde gebruiker mag niet inloggen (US-32)
        if ($gebruiker->geblokkeerd) {
            $this->error = "Dit account is geblokkeerd. Neem contact op met de beheerder.";
            return;
        }

        // Vergelijk ingevoerd wachtwoord met hash in database; bij gelijk: vul sessie en redirect
        if (password_verify($wachtwoord, $gebruiker->wachtwoord)) {
            $_SESSION['gebruiker_id'] = $gebruiker->id;
            $_SESSION['gebruikersnaam'] = $gebruiker->gebruikersnaam;
            $_SESSION['rol_id'] = $gebruiker->rol_id;

            header('Location: ../dashboard/DashboardController.php');
            exit;
        } else {
            $this->error = "Ongeldige gebruikersnaam of wachtwoord.";
        }
    }
}

// Maak controller, roep handleLogin() aan (verwerkt alleen bij POST), daarna login-pagina met eventuele $error
$controller = new LoginController();
$controller->handleLogin();
$error = $controller->error;

// View laden; VIA_CONTROLLER zodat de pagina weet dat ze via controller is aangeroepen
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/login-page/login.php';

