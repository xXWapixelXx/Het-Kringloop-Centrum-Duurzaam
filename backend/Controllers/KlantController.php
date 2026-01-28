<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor klanten beheer

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../DAO/KlantDAO.php';

class KlantController
{
    public $melding = "";
    public $meldingType = "";
    public $klanten = [];

    public $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->dao = new KlantDAO();
        $this->handleActions();
        $this->loadKlanten();
    }

    // check of gebruiker is ingelogd
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: LoginController.php');
            exit;
        }
    }

    // verwerk acties
    public function handleActions()
    {
        // nieuwe klant toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        // klant verwijderen
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Klant verwijderd.";
            $this->meldingType = "warning";
        }
    }

    // voeg nieuwe klant toe
    public function handleToevoegen()
    {
        $voornaam = $_POST['voornaam'];
        $achternaam = $_POST['achternaam'];
        $email = $_POST['email'];
        $telefoonnummer = $_POST['telefoonnummer'];
        $adres = $_POST['adres'];
        $postcode = $_POST['postcode'];
        $woonplaats = $_POST['woonplaats'];

        if (empty($voornaam) || empty($achternaam)) {
            $this->melding = "Voornaam en achternaam zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $klant = new Klant(0, $voornaam, $achternaam, $email, $telefoonnummer, $adres, $postcode, $woonplaats);
        $this->dao->create($klant);
        $this->melding = "Klant succesvol toegevoegd!";
        $this->meldingType = "success";
    }

    // laad klanten
    public function loadKlanten()
    {
        $this->klanten = $this->dao->getAll();
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->klanten);
    }
}

// run controller
$controller = new KlantController();

// laad view
require_once __DIR__ . '/../../frontend/templates/klanten.php';
