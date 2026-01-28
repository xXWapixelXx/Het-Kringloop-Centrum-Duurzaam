<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor dashboard pagina

declare(strict_types=1);

session_start();

class DashboardController
{
    public $gebruikersnaam;
    public $rolNaam;
    public $rolId;

    // rollen namen
    private $rollen = [
        1 => 'Directie',
        2 => 'Magazijn medewerker',
        3 => 'Winkelpersoneel',
        4 => 'Chauffeur'
    ];

    public function __construct()
    {
        $this->checkLogin();
        $this->loadUserData();
    }

    // check of gebruiker is ingelogd
    private function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: LoginController.php');
            exit;
        }
    }

    // laad gebruiker data
    private function loadUserData()
    {
        $this->gebruikersnaam = $_SESSION['gebruikersnaam'];
        $this->rolId = $_SESSION['rol_id'];
        $this->rolNaam = $this->rollen[$this->rolId] ?? 'Onbekend';
    }

    // check of gebruiker directie is
    public function isDirectie()
    {
        return $this->rolId == 1;
    }
}

// run controller
$controller = new DashboardController();

// laad view
require_once __DIR__ . '/../../frontend/templates/dashboard.php';
