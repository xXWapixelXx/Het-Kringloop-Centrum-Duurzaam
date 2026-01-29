<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor voorraad overzicht

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/VoorraadDAO.php';
require_once __DIR__ . '/../../DAO/ArtikelDAO.php';

class VoorraadController
{
    public $melding = "";
    public $meldingType = "";
    public $voorraad = [];
    public $artikelen = [];

    public $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkRol();
        $this->dao = new VoorraadDAO();
        $this->handleActions();
        $this->loadArtikelen();
        $this->loadVoorraad();

        if (isset($_SESSION['voorraad_melding'])) {
            $this->melding = $_SESSION['voorraad_melding'];
            $this->meldingType = $_SESSION['voorraad_melding_type'] ?? 'success';
            unset($_SESSION['voorraad_melding'], $_SESSION['voorraad_melding_type']);
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

    // Directie en Magazijnmedewerker mogen voorraad beheren (PDF 3.5)
    private function checkRol()
    {
        $rol = isset($_SESSION['rol_id']) ? (int)$_SESSION['rol_id'] : 0;
        if ($rol === 1 || $rol === 3) {
            return;
        }
        header('Location: ../dashboard/DashboardController.php');
        exit;
    }

    // verwerk acties
    public function handleActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['voorraad_melding'] = $this->melding;
                $_SESSION['voorraad_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Item verwijderd uit voorraad.";
            $this->meldingType = "warning";
        }
    }

    // laad artikelen voor dropdown
    public function loadArtikelen()
    {
        $artikelDao = new ArtikelDAO();
        $this->artikelen = $artikelDao->getAll();
    }

    // haal artikel naam op bij artikel_id (voor in voorraad tabel)
    public function getArtikelNaam(int $artikelId): string
    {
        foreach ($this->artikelen as $artikel) {
            if ($artikel->id == $artikelId) {
                return $artikel->naam;
            }
        }
        return '-';
    }

    // voeg voorraad toe
    public function handleToevoegen()
    {
        $artikel_id = 0;
        if (isset($_POST['artikel_id'])) {
            $artikel_id = (int)$_POST['artikel_id'];
        }
        $hoeveelheid = 0;
        if (isset($_POST['hoeveelheid'])) {
            $hoeveelheid = (int)$_POST['hoeveelheid'];
        }

        if ($artikel_id === 0 || $hoeveelheid < 1) {
            $this->melding = "Kies een artikel en vul een hoeveelheid in (minimaal 1).";
            $this->meldingType = "danger";
            return;
        }

        $voorraad = new Voorraad(0, $artikel_id, $hoeveelheid);
        $this->dao->create($voorraad);
        $this->melding = "Voorraad toegevoegd.";
        $this->meldingType = "success";
    }

    // laad voorraad
    public function loadVoorraad()
    {
        $this->voorraad = $this->dao->getAll();
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->voorraad);
    }
}

// run controller
$controller = new VoorraadController();

// laad view (markeer dat we via controller komen)
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/voorraad-page/voorraad.php';
