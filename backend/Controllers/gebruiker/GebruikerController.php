<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor gebruikersbeheer (alleen Directie)

declare(strict_types=1);

session_start();

// laad view
require_once __DIR__ . '/../../frontend/templates/gebruikers.html';
require_once __DIR__ . '/../DAO/GebruikerDAO.php';

class GebruikerController
{
    public $melding = "";
    public $meldingType = "";
    public $gebruikers = [];

    // rollen
    public $rollen = [
        1 => 'Directie',
        2 => 'Magazijn medewerker',
        3 => 'Winkelpersoneel',
        4 => 'Chauffeur'
    ];

    private $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkDirectie();
        $this->dao = new GebruikerDAO();
        $this->handleActions();
        $this->loadGebruikers();
    }

    // check of gebruiker is ingelogd
    private function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: LoginController.php');
            exit;
        }
    }

    // check of gebruiker Directie is
    private function checkDirectie()
    {
        if ($_SESSION['rol_id'] != 1) {
            header('Location: DashboardController.php');
            exit;
        }
    }

    // verwerk acties (toevoegen/verwijderen)
    private function handleActions()
    {
        // nieuwe gebruiker toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        // gebruiker verwijderen
        if (isset($_GET['delete'])) {
            $this->handleVerwijderen((int)$_GET['delete']);
        }
    }

    // voeg nieuwe gebruiker toe
    private function handleToevoegen()
    {
        $gebruikersnaam = $_POST['gebruikersnaam'] ?? '';
        $wachtwoord = $_POST['wachtwoord'] ?? '';
        $rol_id = (int)($_POST['rol_id'] ?? 0);

        if (empty($gebruikersnaam) || empty($wachtwoord) || $rol_id == 0) {
            $this->melding = "Vul alle velden in.";
            $this->meldingType = "danger";
            return;
        }

        // check of gebruikersnaam al bestaat
        $bestaand = $this->dao->getByGebruikersnaam($gebruikersnaam);
        if ($bestaand) {
            $this->melding = "Gebruikersnaam bestaat al.";
            $this->meldingType = "danger";
            return;
        }

        $nieuweGebruiker = new Gebruiker(0, $gebruikersnaam, password_hash($wachtwoord, PASSWORD_DEFAULT), $rol_id);
        $this->dao->create($nieuweGebruiker);
        $this->melding = "Gebruiker succesvol toegevoegd!";
        $this->meldingType = "success";
    }

    // verwijder gebruiker
    private function handleVerwijderen($id)
    {
        if ($id == $_SESSION['gebruiker_id']) {
            $this->melding = "Je kunt jezelf niet verwijderen.";
            $this->meldingType = "warning";
            return;
        }

        $this->dao->delete($id);
        $this->melding = "Gebruiker verwijderd.";
        $this->meldingType = "warning";
    }

    // laad alle gebruikers
    private function loadGebruikers()
    {
        $this->gebruikers = $this->dao->getAll();
    }

    // geef rol badge kleur
    public function getRolKleur($rolId)
    {
        return match($rolId) {
            1 => 'danger',
            2 => 'primary',
            3 => 'success',
            4 => 'info',
            default => 'secondary'
        };
    }

    // check of het huidige gebruiker is
    public function isHuidigeGebruiker($id)
    {
        return $id == $_SESSION['gebruiker_id'];
    }
}

// run controller
$controller = new GebruikerController();
