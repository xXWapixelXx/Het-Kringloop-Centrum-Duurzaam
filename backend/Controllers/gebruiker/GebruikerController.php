<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor gebruikersbeheer (alleen Directie)

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/GebruikerDAO.php';

class GebruikerController
{
    public $melding = "";
    public $meldingType = "";
    public $gebruikers = [];
    public $editGebruiker = null;

    // rollen (zelfde volgorde als Config/rollen.php)
    public $rollen = [
        1 => 'Directie',
        2 => 'Winkelpersoneel',
        3 => 'Magazijnmedewerker',
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

        if (isset($_SESSION['gebruiker_melding'])) {
            $this->melding = $_SESSION['gebruiker_melding'];
            $this->meldingType = $_SESSION['gebruiker_melding_type'] ?? 'success';
            unset($_SESSION['gebruiker_melding'], $_SESSION['gebruiker_melding_type']);
        }
    }

    // check of gebruiker is ingelogd
    private function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // check of gebruiker Directie is
    private function checkDirectie()
    {
        if ($_SESSION['rol_id'] != 1) {
            header('Location: ../dashboard/DashboardController.php');
            exit;
        }
    }

    // verwerk acties
    private function handleActions()
    {
        // nieuwe gebruiker toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        // gebruiker bewerken (rol wijzigen)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
        }

        // gebruiker verwijderen
        if (isset($_GET['delete'])) {
            $this->handleVerwijderen((int)$_GET['delete']);
        }

        // gebruiker blokkeren/deblokkeren (US-32)
        if (isset($_GET['blokkeer'])) {
            $this->handleBlokkeer((int)$_GET['blokkeer']);
        }

        // wachtwoord resetten (US-7)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'reset_wachtwoord') {
            $this->handleResetWachtwoord();
        }

        // edit mode laden
        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editGebruiker = $this->dao->getById($id);
        }
    }

    // voeg nieuwe gebruiker toe
    private function handleToevoegen()
    {
        $gebruikersnaam = trim($_POST['gebruikersnaam'] ?? '');
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

        $_SESSION['gebruiker_melding'] = "Gebruiker toegevoegd!";
        $_SESSION['gebruiker_melding_type'] = "success";
        header('Location: GebruikerController.php');
        exit;
    }

    // bewerk gebruiker (rol wijzigen)
    private function handleBewerken()
    {
        $id = (int)($_POST['id'] ?? 0);
        $rol_id = (int)($_POST['rol_id'] ?? 0);

        if ($id == 0 || $rol_id == 0) {
            $this->melding = "Ongeldige gegevens.";
            $this->meldingType = "danger";
            return;
        }

        // haal bestaande gebruiker op
        $gebruiker = $this->dao->getById($id);
        if (!$gebruiker) {
            $this->melding = "Gebruiker niet gevonden.";
            $this->meldingType = "danger";
            return;
        }

        // update rol
        $gebruiker->rol_id = $rol_id;
        $this->dao->update($gebruiker);

        $_SESSION['gebruiker_melding'] = "Rol gewijzigd!";
        $_SESSION['gebruiker_melding_type'] = "success";
        header('Location: GebruikerController.php');
        exit;
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
        $_SESSION['gebruiker_melding'] = "Gebruiker verwijderd.";
        $_SESSION['gebruiker_melding_type'] = "warning";
        header('Location: GebruikerController.php');
        exit;
    }

    // blokkeer of deblokkeer gebruiker (US-32)
    private function handleBlokkeer($id)
    {
        if ($id == $_SESSION['gebruiker_id']) {
            $this->melding = "Je kunt jezelf niet blokkeren.";
            $this->meldingType = "warning";
            return;
        }

        $gebruiker = $this->dao->getById($id);
        if (!$gebruiker) {
            $this->melding = "Gebruiker niet gevonden.";
            $this->meldingType = "danger";
            return;
        }

        $this->dao->toggleBlokkeer($id);
        $actie = $gebruiker->geblokkeerd ? "gedeblokkeerd" : "geblokkeerd";
        $_SESSION['gebruiker_melding'] = "Gebruiker " . $actie . ".";
        $_SESSION['gebruiker_melding_type'] = $gebruiker->geblokkeerd ? "success" : "warning";
        header('Location: GebruikerController.php');
        exit;
    }

    // reset wachtwoord voor gebruiker (US-7)
    private function handleResetWachtwoord()
    {
        $id = (int)($_POST['id'] ?? 0);
        $nieuwWachtwoord = $_POST['nieuw_wachtwoord'] ?? '';

        if ($id == 0 || $nieuwWachtwoord === '') {
            $this->melding = "Vul een nieuw wachtwoord in.";
            $this->meldingType = "danger";
            return;
        }

        $gebruiker = $this->dao->getById($id);
        if (!$gebruiker) {
            $this->melding = "Gebruiker niet gevonden.";
            $this->meldingType = "danger";
            return;
        }

        // update wachtwoord
        $gebruiker->wachtwoord = password_hash($nieuwWachtwoord, PASSWORD_DEFAULT);
        $this->dao->update($gebruiker);

        $_SESSION['gebruiker_melding'] = "Wachtwoord gereset voor " . $gebruiker->gebruikersnaam . ".";
        $_SESSION['gebruiker_melding_type'] = "success";
        header('Location: GebruikerController.php');
        exit;
    }

    // laad alle gebruikers
    private function loadGebruikers()
    {
        $this->gebruikers = $this->dao->getAll();
    }

    // check of het huidige gebruiker is
    public function isHuidigeGebruiker($id)
    {
        return $id == $_SESSION['gebruiker_id'];
    }
}

// run controller
$controller = new GebruikerController();

// laad view
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/gebruikers-page/gebruikers.php';
