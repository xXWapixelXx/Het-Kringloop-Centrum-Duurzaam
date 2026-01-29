<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor donateurs. Constructor: checkLogin, checkRol (alleen rol 1), DAO, acties, loadDonateurs. Alleen Directie mag deze pagina (PDF 3.5). Optioneel zoeken op naam.

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/DonateurDAO.php';

class DonateurController
{
    public $melding = "";
    public $meldingType = "";
    public $donateurs = [];
    public $editDonateur = null;
    public $zoekterm = "";

    private $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkRol();
        $this->dao = new DonateurDAO();
        $this->handleActions();
        $this->loadDonateurs();

        if (isset($_SESSION['donateur_melding'])) {
            $this->melding = $_SESSION['donateur_melding'];
            $this->meldingType = $_SESSION['donateur_melding_type'] ?? 'success';
            unset($_SESSION['donateur_melding'], $_SESSION['donateur_melding_type']);
        }
    }

    // Geen sessie = redirect naar login
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // Alleen rol_id 1 (Directie) mag; anders redirect naar dashboard
    private function checkRol()
    {
        if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1) {
            return;
        }
        header('Location: ../dashboard/DashboardController.php');
        exit;
    }

    // POST toevoegen/bewerken (redirect na success); GET delete/edit/zoek
    public function handleActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['donateur_melding'] = $this->melding;
                $_SESSION['donateur_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
            if ($this->meldingType === 'success') {
                $_SESSION['donateur_melding'] = $this->melding;
                $_SESSION['donateur_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $_SESSION['donateur_melding'] = "Donateur verwijderd.";
            $_SESSION['donateur_melding_type'] = "warning";
            header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
            exit;
        }

        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editDonateur = $this->dao->getById($id);
        }

        if (isset($_GET['zoek']) && !empty($_GET['zoek'])) {
            $this->zoekterm = trim($_GET['zoek']);
        }
    }

    // Valideer voornaam/achternaam; maak Donateur-model, DAO create, melding
    public function handleToevoegen()
    {
        $voornaam = trim($_POST['voornaam'] ?? '');
        $achternaam = trim($_POST['achternaam'] ?? '');
        $adres = trim($_POST['adres'] ?? '');
        $plaats = trim($_POST['plaats'] ?? '');
        $telefoon = trim($_POST['telefoon'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $geboortedatum = $_POST['geboortedatum'] ?? '';
        $datum_ingevoerd = date('Y-m-d');

        if ($voornaam === '' || $achternaam === '') {
            $this->melding = "Voornaam en achternaam zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $donateur = new Donateur(0, $voornaam, $achternaam, $adres, $plaats, $telefoon, $email, $geboortedatum, $datum_ingevoerd);
        $this->dao->create($donateur);
        $this->melding = "Donateur toegevoegd.";
        $this->meldingType = "success";
    }

    // Valideer id en velden; maak Donateur-model, DAO update, melding
    public function handleBewerken()
    {
        $id = (int)($_POST['id'] ?? 0);
        $voornaam = trim($_POST['voornaam'] ?? '');
        $achternaam = trim($_POST['achternaam'] ?? '');
        $adres = trim($_POST['adres'] ?? '');
        $plaats = trim($_POST['plaats'] ?? '');
        $telefoon = trim($_POST['telefoon'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $geboortedatum = $_POST['geboortedatum'] ?? '';
        $datum_ingevoerd = $_POST['datum_ingevoerd'] ?? date('Y-m-d');

        if ($id === 0 || $voornaam === '' || $achternaam === '') {
            $this->melding = "Voornaam en achternaam zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $donateur = new Donateur($id, $voornaam, $achternaam, $adres, $plaats, $telefoon, $email, $geboortedatum, $datum_ingevoerd);
        $this->dao->update($donateur);
        $this->melding = "Donateur bijgewerkt.";
        $this->meldingType = "success";
    }

    // Met zoekterm: DAO zoekOpNaam; anders DAO getAll; vult $donateurs voor de view
    public function loadDonateurs()
    {
        if (!empty($this->zoekterm)) {
            $this->donateurs = $this->dao->zoekOpNaam($this->zoekterm);
        } else {
            $this->donateurs = $this->dao->getAll();
        }
    }

    public function countResultaten()
    {
        return count($this->donateurs);
    }
}

// Maak controller; daarna view
$controller = new DonateurController();

// View laden; VIA_CONTROLLER
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/donateur-page/donateur.php';
