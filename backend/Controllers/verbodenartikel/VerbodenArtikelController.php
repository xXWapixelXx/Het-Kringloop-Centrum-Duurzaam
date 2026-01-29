<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor verboden artikelen beheer (alleen Directie)

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/VerbodenArtikelDAO.php';
require_once __DIR__ . '/../../Models/VerbodenArtikel.php';

class VerbodenArtikelController
{
    public $melding = "";
    public $meldingType = "";
    public $verbodenArtikelen = [];

    public $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkDirectie();
        $this->dao = new VerbodenArtikelDAO();
        $this->handleActions();
        $this->loadVerbodenArtikelen();
    }

    // check of gebruiker is ingelogd
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // check of gebruiker Directie is
    public function checkDirectie()
    {
        if ($_SESSION['rol_id'] != 1) {
            header('Location: ../dashboard/DashboardController.php');
            exit;
        }
    }

    // verwerk acties
    public function handleActions()
    {
        // nieuw verboden artikel toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        // verboden artikel verwijderen
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Verboden artikel verwijderd.";
            $this->meldingType = "warning";
        }
    }

    // voeg nieuw verboden artikel toe
    public function handleToevoegen()
    {
        $omschrijving = "";
        if (isset($_POST['omschrijving'])) {
            $omschrijving = trim($_POST['omschrijving']);
        }

        if ($omschrijving === "") {
            $this->melding = "Vul een omschrijving in.";
            $this->meldingType = "danger";
            return;
        }

        $verbodenArtikel = new VerbodenArtikel(0, $omschrijving);
        $this->dao->create($verbodenArtikel);
        $this->melding = "Verboden artikel toegevoegd.";
        $this->meldingType = "success";
    }

    // laad verboden artikelen
    public function loadVerbodenArtikelen()
    {
        $this->verbodenArtikelen = $this->dao->getAll();
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->verbodenArtikelen);
    }
}

// run controller
$controller = new VerbodenArtikelController();

// laad view (markeer dat we via controller komen)
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/verbodenartikel-page/verbodenartikel.php';
