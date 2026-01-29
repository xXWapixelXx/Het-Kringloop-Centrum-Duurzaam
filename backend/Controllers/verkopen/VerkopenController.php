<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor verkopen beheer en omzet overzicht

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/VerkopenDAO.php';
require_once __DIR__ . '/../../DAO/ArtikelDAO.php';
require_once __DIR__ . '/../../DAO/KlantDAO.php';

class VerkopenController
{
    public $melding = "";
    public $meldingType = "";
    public $verkopen = [];
    public $artikelen = [];
    public $klanten = [];
    public $editVerkoop = null;

    // filters
    public $filterVanDatum = "";
    public $filterTotDatum = "";

    private $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkRol();
        $this->dao = new VerkopenDAO();
        $this->handleFilters();
        $this->handleActions();
        $this->loadArtikelen();
        $this->loadKlanten();
        $this->loadVerkopen();

        if (isset($_SESSION['verkopen_melding'])) {
            $this->melding = $_SESSION['verkopen_melding'];
            $this->meldingType = $_SESSION['verkopen_melding_type'] ?? 'success';
            unset($_SESSION['verkopen_melding'], $_SESSION['verkopen_melding_type']);
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

    // Directie en Winkelpersoneel mogen verkopen beheren (PDF 3.5)
    private function checkRol()
    {
        $rol = isset($_SESSION['rol_id']) ? (int)$_SESSION['rol_id'] : 0;
        if ($rol === 1 || $rol === 2) {
            return;
        }
        header('Location: ../dashboard/DashboardController.php');
        exit;
    }

    // verwerk filters
    public function handleFilters()
    {
        if (isset($_GET['van_datum']) && !empty($_GET['van_datum'])) {
            $this->filterVanDatum = $_GET['van_datum'];
        }
        if (isset($_GET['tot_datum']) && !empty($_GET['tot_datum'])) {
            $this->filterTotDatum = $_GET['tot_datum'];
        }
    }

    // verwerk acties
    public function handleActions()
    {
        // toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['verkopen_melding'] = $this->melding;
                $_SESSION['verkopen_melding_type'] = $this->meldingType;
                // redirect naar zichzelf om herladen te voorkomen
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // bewerken
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
            if ($this->meldingType === 'success') {
                $_SESSION['verkopen_melding'] = $this->melding;
                $_SESSION['verkopen_melding_type'] = $this->meldingType;
                // redirect naar zichzelf om herladen te voorkomen
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // verwijderen
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $_SESSION['verkopen_melding'] = "Verkoop verwijderd.";
            $_SESSION['verkopen_melding_type'] = "warning";
            // redirect naar zichzelf om herladen te voorkomen
            header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
            exit;
        }

        // edit mode laden
        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editVerkoop = $this->dao->getById($id);
        }
    }

    // laad artikelen voor dropdown
    public function loadArtikelen()
    {
        $artikelDao = new ArtikelDAO();
        $this->artikelen = $artikelDao->getAll();
    }

    // laad klanten voor dropdown
    public function loadKlanten()
    {
        $klantDao = new KlantDAO();
        $this->klanten = $klantDao->getAll();
    }

    // voeg verkoop toe
    public function handleToevoegen()
    {
        $klant_id = 0;
        if (isset($_POST['klant_id'])) {
            $klant_id = (int)$_POST['klant_id'];
        }
        $artikel_id = 0;
        if (isset($_POST['artikel_id'])) {
            $artikel_id = (int)$_POST['artikel_id'];
        }
        $verkoop_prijs_ex_btw = 0.0;
        if (isset($_POST['verkoop_prijs_ex_btw'])) {
            $verkoop_prijs_ex_btw = (float)$_POST['verkoop_prijs_ex_btw'];
        }
        $verkocht_op = isset($_POST['verkocht_op']) ? $_POST['verkocht_op'] : date('Y-m-d');

        if ($artikel_id === 0 || $verkoop_prijs_ex_btw <= 0) {
            $this->melding = "Artikel en prijs zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $verkoop = new Verkopen(0, $klant_id, $artikel_id, $verkoop_prijs_ex_btw, $verkocht_op);
        $this->dao->create($verkoop);
        $this->melding = "Verkoop geregistreerd.";
        $this->meldingType = "success";
    }

    // bewerk verkoop
    public function handleBewerken()
    {
        $id = 0;
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $klant_id = 0;
        if (isset($_POST['klant_id'])) {
            $klant_id = (int)$_POST['klant_id'];
        }
        $artikel_id = 0;
        if (isset($_POST['artikel_id'])) {
            $artikel_id = (int)$_POST['artikel_id'];
        }
        $verkoop_prijs_ex_btw = 0.0;
        if (isset($_POST['verkoop_prijs_ex_btw'])) {
            $verkoop_prijs_ex_btw = (float)$_POST['verkoop_prijs_ex_btw'];
        }
        $verkocht_op = isset($_POST['verkocht_op']) ? $_POST['verkocht_op'] : date('Y-m-d');

        if ($id === 0 || $artikel_id === 0 || $verkoop_prijs_ex_btw <= 0) {
            $this->melding = "Artikel en prijs zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $verkoop = new Verkopen($id, $klant_id, $artikel_id, $verkoop_prijs_ex_btw, $verkocht_op);
        $this->dao->update($verkoop);
        $this->melding = "Verkoop bijgewerkt.";
        $this->meldingType = "success";
    }

    // laad verkopen (met optionele datumfilter)
    public function loadVerkopen()
    {
        $alleVerkopen = $this->dao->getAll();

        // filter op datum als nodig
        if (!empty($this->filterVanDatum) || !empty($this->filterTotDatum)) {
            $gefilterd = [];
            foreach ($alleVerkopen as $verkoop) {
                $verkoopDatum = strtotime($verkoop->verkocht_op);

                $vanOk = empty($this->filterVanDatum) || $verkoopDatum >= strtotime($this->filterVanDatum);
                $totOk = empty($this->filterTotDatum) || $verkoopDatum <= strtotime($this->filterTotDatum . ' 23:59:59');

                if ($vanOk && $totOk) {
                    $gefilterd[] = $verkoop;
                }
            }
            $this->verkopen = $gefilterd;
        } else {
            $this->verkopen = $alleVerkopen;
        }
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->verkopen);
    }

    // bereken totale omzet (excl BTW)
    public function berekenTotaalExBtw(): float
    {
        $totaal = 0.0;
        foreach ($this->verkopen as $verkoop) {
            $totaal += $verkoop->verkoop_prijs_ex_btw;
        }
        return $totaal;
    }

    // bereken totale omzet (incl BTW)
    public function berekenTotaalIncBtw(): float
    {
        $totaal = 0.0;
        foreach ($this->verkopen as $verkoop) {
            $totaal += $verkoop->getVerkoopPrijsIncBtw();
        }
        return $totaal;
    }

    // haal klant naam op
    public function getKlantNaam(int $klantId): string
    {
        if ($klantId === 0) return 'Anoniem';
        foreach ($this->klanten as $klant) {
            if ($klant->id == $klantId) {
                return $klant->naam;
            }
        }
        return '-';
    }

    // haal artikel naam op
    public function getArtikelNaam(int $artikelId): string
    {
        foreach ($this->artikelen as $artikel) {
            if ($artikel->id == $artikelId) {
                return $artikel->naam;
            }
        }
        return '-';
    }
}

// run controller
$controller = new VerkopenController();

// laad view
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/verkopen-page/verkopen.php';
