<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor verboden artikelen. Constructor: eerst checkLogin, dan checkDirectie, daarna DAO/acties/laden. Alleen Directie (rol 1) mag deze pagina.

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

    // Volgorde belangrijk: eerst checkLogin (anders rol_id bestaat niet), dan checkDirectie (rol 1 = Directie), daarna DAO en acties
    public function __construct()
    {
        $this->checkLogin();
        $this->checkDirectie();
        $this->dao = new VerbodenArtikelDAO();
        $this->handleActions();
        $this->loadVerbodenArtikelen();
    }

    // Geen sessie (gebruiker_id) = niet ingelogd; redirect naar login
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // rol_id != 1 = geen Directie; redirect naar dashboard (deze pagina mag alleen Directie)
    public function checkDirectie()
    {
        if ($_SESSION['rol_id'] != 1) {
            header('Location: ../dashboard/DashboardController.php');
            exit;
        }
    }

    // POST met actie=toevoegen -> handleToevoegen; GET met delete -> DAO delete
    public function handleActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Verboden artikel verwijderd.";
            $this->meldingType = "warning";
        }
    }

    // Valideer omschrijving, maak VerbodenArtikel-model, DAO create, melding tonen
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

    // DAO haalt alle rijen op; we vullen $verbodenArtikelen voor de view
    public function loadVerbodenArtikelen()
    {
        $this->verbodenArtikelen = $this->dao->getAll();
    }

    public function countResultaten()
    {
        return count($this->verbodenArtikelen);
    }
}

// Maak controller (constructor doet checkLogin, checkDirectie, acties, laden); daarna view
$controller = new VerbodenArtikelController();

// View laden; VIA_CONTROLLER zodat de pagina weet dat ze via controller komt
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/verbodenartikel-page/verbodenartikel.php';
