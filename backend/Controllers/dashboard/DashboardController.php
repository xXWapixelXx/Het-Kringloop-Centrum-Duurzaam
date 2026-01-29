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
    public $isIngelogd;

    public function __construct()
    {
        $this->checkLogin();
        if ($this->isIngelogd) {
            $this->loadUserData();
        }
    }

    // check of gebruiker is ingelogd
    private function checkLogin()
    {
        if (isset($_SESSION['gebruiker_id'])) {
            $this->isIngelogd = true;
        } else {
            $this->isIngelogd = false;
        }
    }

    // laad gebruiker data
    private function loadUserData()
    {
        $this->gebruikersnaam = $_SESSION['gebruikersnaam'];
        $this->rolId = $_SESSION['rol_id'];

        // laad rol naam uit rollen mapping
        require __DIR__ . '/../../Config/rollen.php';
        $this->rolNaam = $ROL_LEN[$this->rolId] ?? 'Onbekend';
    }
}

// run controller
$controller = new DashboardController();

// laad view (markeer dat we via controller komen, anders zou view direct openen)
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/dashboard-page/dashboard.php';
