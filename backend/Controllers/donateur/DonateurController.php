<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor donateurs beheer (mensen die goederen doneren)

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

    // check of gebruiker is ingelogd
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // alleen Directie mag persoonsgegevens/donateurs beheren (PDF 3.5)
    private function checkRol()
    {
        if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1) {
            return;
        }
        header('Location: ../dashboard/DashboardController.php');
        exit;
    }

    // verwerk acties
    public function handleActions()
    {
        // toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['donateur_melding'] = $this->melding;
                $_SESSION['donateur_melding_type'] = $this->meldingType;
                // redirect naar zichzelf om herladen te voorkomen
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // bewerken
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
            if ($this->meldingType === 'success') {
                $_SESSION['donateur_melding'] = $this->melding;
                $_SESSION['donateur_melding_type'] = $this->meldingType;
                // redirect naar zichzelf om herladen te voorkomen
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // verwijderen
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $_SESSION['donateur_melding'] = "Donateur verwijderd.";
            $_SESSION['donateur_melding_type'] = "warning";
            // redirect naar zichzelf om herladen te voorkomen
            header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
            exit;
        }

        // edit mode laden
        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editDonateur = $this->dao->getById($id);
        }

        // zoeken
        if (isset($_GET['zoek']) && !empty($_GET['zoek'])) {
            $this->zoekterm = trim($_GET['zoek']);
        }
    }

    // voeg donateur toe
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

    // bewerk donateur
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

    // laad donateurs (met optionele zoekfunctie)
    public function loadDonateurs()
    {
        if (!empty($this->zoekterm)) {
            $this->donateurs = $this->dao->zoekOpNaam($this->zoekterm);
        } else {
            $this->donateurs = $this->dao->getAll();
        }
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->donateurs);
    }
}

// run controller
$controller = new DonateurController();

// laad view
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/donateur-page/donateur.php';
