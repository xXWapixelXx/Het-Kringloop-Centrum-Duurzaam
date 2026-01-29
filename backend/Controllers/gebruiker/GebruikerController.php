<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor gebruikersbeheer. Constructor: checkLogin, checkDirectie (rol 1), DAO, handleActions, loadGebruikers. Alleen Directie mag deze pagina.

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/GebruikerDAO.php';

class GebruikerController
{
    public $melding = "";
    public $meldingType = "";
    public $gebruikers = [];
    public $editGebruiker = null;

    // Mapping rol_id -> rolnaam (zelfde als Config/rollen.php)
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

    // Geen sessie = redirect naar login
    private function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // rol_id != 1 = geen Directie; redirect naar dashboard
    private function checkDirectie()
    {
        if ($_SESSION['rol_id'] != 1) {
            header('Location: ../dashboard/DashboardController.php');
            exit;
        }
    }

    // POST toevoegen/bewerken/reset_wachtwoord; GET delete/blokkeer/edit
    private function handleActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
        }

        if (isset($_GET['delete'])) {
            $this->handleVerwijderen((int)$_GET['delete']);
        }

        if (isset($_GET['blokkeer'])) {
            $this->handleBlokkeer((int)$_GET['blokkeer']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'reset_wachtwoord') {
            $this->handleResetWachtwoord();
        }

        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editGebruiker = $this->dao->getById($id);
        }
    }

    // Valideer velden; check of gebruikersnaam al bestaat; password_hash; DAO create; redirect
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

    // Haal gebruiker op; update rol_id; DAO update; redirect
    private function handleBewerken()
    {
        $id = (int)($_POST['id'] ?? 0);
        $rol_id = (int)($_POST['rol_id'] ?? 0);

        if ($id == 0 || $rol_id == 0) {
            $this->melding = "Ongeldige gegevens.";
            $this->meldingType = "danger";
            return;
        }

        $gebruiker = $this->dao->getById($id);
        if (!$gebruiker) {
            $this->melding = "Gebruiker niet gevonden.";
            $this->meldingType = "danger";
            return;
        }

        $gebruiker->rol_id = $rol_id;
        $this->dao->update($gebruiker);

        $_SESSION['gebruiker_melding'] = "Rol gewijzigd!";
        $_SESSION['gebruiker_melding_type'] = "success";
        header('Location: GebruikerController.php');
        exit;
    }

    // Mag niet jezelf verwijderen; anders DAO delete, redirect
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

    // Mag niet jezelf blokkeren; DAO toggleBlokkeer (US-32); redirect
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

    // Valideer id en nieuw wachtwoord; password_hash; DAO update; redirect (US-7)
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

        $gebruiker->wachtwoord = password_hash($nieuwWachtwoord, PASSWORD_DEFAULT);
        $this->dao->update($gebruiker);

        $_SESSION['gebruiker_melding'] = "Wachtwoord gereset voor " . $gebruiker->gebruikersnaam . ".";
        $_SESSION['gebruiker_melding_type'] = "success";
        header('Location: GebruikerController.php');
        exit;
    }

    // DAO getAll; vult $gebruikers voor de view
    private function loadGebruikers()
    {
        $this->gebruikers = $this->dao->getAll();
    }

    // Voor view: mag deze rij bewerken/verwijderen? (niet jezelf)
    public function isHuidigeGebruiker($id)
    {
        return $id == $_SESSION['gebruiker_id'];
    }
}

// Maak controller; daarna view
$controller = new GebruikerController();

// View laden; VIA_CONTROLLER
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/gebruikers-page/gebruikers.php';
