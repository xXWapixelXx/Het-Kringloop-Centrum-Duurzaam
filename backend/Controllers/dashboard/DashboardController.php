<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor dashboard. Constructor: checkLogin (sessie?), daarna loadUserData uit sessie en rollen-config.

declare(strict_types=1);

session_start();

class DashboardController
{
    public $gebruikersnaam;
    public $rolNaam;
    public $rolId;
    public $isIngelogd;

    public function __construct()
    {
        $this->checkLogin();
        if ($this->isIngelogd) {
            $this->loadUserData();
        }
    }

    // Geen sessie (gebruiker_id) = niet ingelogd; zet isIngelogd true/false
    private function checkLogin()
    {
        if (isset($_SESSION['gebruiker_id'])) {
            $this->isIngelogd = true;
        } else {
            $this->isIngelogd = false;
        }
    }

    // Haal gebruikersnaam en rol_id uit sessie; rolnaam uit Config/rollen.php (ROL_LEN)
    private function loadUserData()
    {
        $this->gebruikersnaam = $_SESSION['gebruikersnaam'];
        $this->rolId = $_SESSION['rol_id'];

        require __DIR__ . '/../../Config/rollen.php';
        $this->rolNaam = $ROL_LEN[$this->rolId] ?? 'Onbekend';
    }
}

// Maak controller (checkLogin, loadUserData); daarna view
$controller = new DashboardController();

// View laden; VIA_CONTROLLER zodat de pagina weet dat ze via controller komt
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/dashboard-page/dashboard.php';
