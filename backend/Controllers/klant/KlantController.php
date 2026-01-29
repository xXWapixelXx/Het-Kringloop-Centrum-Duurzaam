<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor klanten. Constructor: checkLogin, checkRol (alleen rol 1), DAO, acties, laadTeBewerkenKlant, loadKlanten. Alleen Directie mag deze pagina (PDF 3.5).

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/KlantDAO.php';

class KlantController
{
    public $melding = "";
    public $meldingType = "";
    public $klanten = [];
    public $teBewerkenKlant = null;

    public $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkRol();
        $this->dao = new KlantDAO();
        $this->handleActions();
        $this->laadTeBewerkenKlant();
        $this->loadKlanten();

        if (isset($_SESSION['klant_melding'])) {
            $this->melding = $_SESSION['klant_melding'];
            $this->meldingType = $_SESSION['klant_melding_type'] ?? 'success';
            unset($_SESSION['klant_melding'], $_SESSION['klant_melding_type']);
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

    // POST toevoegen/bewerken (redirect na success); GET delete
    public function handleActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['klant_melding'] = $this->melding;
                $_SESSION['klant_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
        }

        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Klant verwijderd.";
            $this->meldingType = "warning";
        }
    }

    // Bij ?edit=id: DAO getById; vult teBewerkenKlant voor formulier
    public function laadTeBewerkenKlant()
    {
        if (isset($_GET['edit']) && $_GET['edit'] !== '') {
            $id = (int)$_GET['edit'];
            if ($id > 0) {
                $this->teBewerkenKlant = $this->dao->getById($id);
            }
        }
    }

    // Valideer id en naam; maak Klant-model, DAO update, redirect
    public function handleBewerken()
    {
        $id = (int)($_POST['id'] ?? 0);
        $naam = trim($_POST['naam'] ?? '');
        $adres = trim($_POST['adres'] ?? '');
        $plaats = trim($_POST['plaats'] ?? '');
        $telefoon = trim($_POST['telefoon'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($id === 0 || empty($naam)) {
            $this->melding = "Klant niet gevonden of naam is leeg.";
            $this->meldingType = "danger";
            return;
        }

        $klant = new Klant($id, $naam, $adres, $plaats, $telefoon, $email);
        $this->dao->update($klant);
        $_SESSION['klant_melding'] = "Klant bijgewerkt.";
        $_SESSION['klant_melding_type'] = "success";
        header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
        exit;
    }

    // Valideer voornaam/achternaam; maak Klant-model, DAO create, melding
    public function handleToevoegen()
    {
        $voornaam = trim($_POST['voornaam'] ?? '');
        $achternaam = trim($_POST['achternaam'] ?? '');

        if (empty($voornaam) || empty($achternaam)) {
            $this->melding = "Voornaam en achternaam zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $naam = $voornaam . ' ' . $achternaam;
        $adres = trim($_POST['adres'] ?? '');
        $postcode = trim($_POST['postcode'] ?? '');
        $adresStr = $adres . ($postcode !== '' ? ', ' . $postcode : '');
        $plaats = trim($_POST['woonplaats'] ?? '');
        $telefoon = trim($_POST['telefoonnummer'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $klant = new Klant(0, $naam, $adresStr, $plaats, $telefoon, $email);
        $this->dao->create($klant);
        $this->melding = "Klant succesvol toegevoegd!";
        $this->meldingType = "success";
    }

    // DAO getAll; vult $klanten voor de view
    public function loadKlanten()
    {
        $this->klanten = $this->dao->getAll();
    }

    public function countResultaten()
    {
        return count($this->klanten);
    }
}

// Maak controller; daarna view
$controller = new KlantController();

// View laden; VIA_CONTROLLER
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/klanten-page/klanten.php';
