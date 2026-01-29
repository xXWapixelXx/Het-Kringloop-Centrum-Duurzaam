<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Versie: 1.0
// Datum: 28-01-2026
// Beschrijving: Controller voor login functionaliteit

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/GebruikerDAO.php';

class LoginController
{
    public $error = "";

    // verwerk login
    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        // trim gebruikersnaam en blokkeer lege velden
        $gebruikersnaam = isset($_POST['gebruikersnaam']) ? trim($_POST['gebruikersnaam']) : '';
        $wachtwoord = isset($_POST['wachtwoord']) ? $_POST['wachtwoord'] : '';

        if ($gebruikersnaam === '' || $wachtwoord === '') {
            $this->error = "Vul alle velden in.";
            return;
        }

        // simpele validatie: lengte tussen 3 en 20 en geen speciale tekens
        $lengte = strlen($gebruikersnaam);
        if ($lengte < 3 || $lengte > 20) {
            $this->error = "Gebruikersnaam moet tussen 3 en 20 tekens zijn.";
            return;
        }
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $gebruikersnaam)) {
            $this->error = "Gebruikersnaam mag alleen letters, cijfers en een underscore bevatten.";
            return;
        }

        $dao = new GebruikerDAO();
        $gebruiker = $dao->getByGebruikersnaam($gebruikersnaam);

        if (!$gebruiker) {
            $this->error = "Ongeldige gebruikersnaam of wachtwoord.";
            return;
        }

        // controleer of gebruiker geblokkeerd is (US-32)
        if ($gebruiker->geblokkeerd) {
            $this->error = "Dit account is geblokkeerd. Neem contact op met de beheerder.";
            return;
        }

        if (password_verify($wachtwoord, $gebruiker->wachtwoord)) {
            $_SESSION['gebruiker_id'] = $gebruiker->id;
            $_SESSION['gebruikersnaam'] = $gebruiker->gebruikersnaam;
            $_SESSION['rol_id'] = $gebruiker->rol_id;

            // stuur gebruiker door naar dashboard controller
            header('Location: ../dashboard/DashboardController.php');
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

// laad de login pagina (markeer dat we via controller komen)
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/login-page/login.php';

