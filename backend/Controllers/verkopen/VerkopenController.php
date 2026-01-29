<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor verkopen en omzet. Constructor: checkLogin, checkRol (rol 1 of 2), DAO, handleFilters, handleActions, loadArtikelen/Klanten/Verkopen. Alleen Directie en Winkelpersoneel (PDF 3.5). Optioneel datumfilter.

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

    // Geen sessie = redirect naar login
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // rol 1 (Directie) of 2 (Winkelpersoneel) mag; anders redirect naar dashboard
    private function checkRol()
    {
        $rol = isset($_SESSION['rol_id']) ? (int)$_SESSION['rol_id'] : 0;
        if ($rol === 1 || $rol === 2) {
            return;
        }
        header('Location: ../dashboard/DashboardController.php');
        exit;
    }

    // Haal van_datum en tot_datum uit GET voor filter
    public function handleFilters()
    {
        if (isset($_GET['van_datum']) && !empty($_GET['van_datum'])) {
            $this->filterVanDatum = $_GET['van_datum'];
        }
        if (isset($_GET['tot_datum']) && !empty($_GET['tot_datum'])) {
            $this->filterTotDatum = $_GET['tot_datum'];
        }
    }

    // POST toevoegen/bewerken (redirect na success); GET delete/edit
    public function handleActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['verkopen_melding'] = $this->melding;
                $_SESSION['verkopen_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
            if ($this->meldingType === 'success') {
                $_SESSION['verkopen_melding'] = $this->melding;
                $_SESSION['verkopen_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $_SESSION['verkopen_melding'] = "Verkoop verwijderd.";
            $_SESSION['verkopen_melding_type'] = "warning";
            header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
            exit;
        }

        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editVerkoop = $this->dao->getById($id);
        }
    }

    // ArtikelDAO getAll voor dropdown (artikel kiezen bij verkoop)
    public function loadArtikelen()
    {
        $artikelDao = new ArtikelDAO();
        $this->artikelen = $artikelDao->getAll();
    }

    // KlantDAO getAll voor dropdown (klant kiezen bij verkoop)
    public function loadKlanten()
    {
        $klantDao = new KlantDAO();
        $this->klanten = $klantDao->getAll();
    }

    // Valideer artikel_id en prijs; maak Verkopen-model, DAO create, melding
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

    // Valideer id, artikel_id, prijs; maak Verkopen-model, DAO update, melding
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

    // DAO getAll; filter op datum als van_datum/tot_datum gezet; vult $verkopen voor view
    public function loadVerkopen()
    {
        $alleVerkopen = $this->dao->getAll();

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

    public function countResultaten()
    {
        return count($this->verkopen);
    }

    // Som van verkoop_prijs_ex_btw van alle getoonde verkopen
    public function berekenTotaalExBtw(): float
    {
        $totaal = 0.0;
        foreach ($this->verkopen as $verkoop) {
            $totaal += $verkoop->verkoop_prijs_ex_btw;
        }
        return $totaal;
    }

    // Som van getVerkoopPrijsIncBtw() van alle getoonde verkopen
    public function berekenTotaalIncBtw(): float
    {
        $totaal = 0.0;
        foreach ($this->verkopen as $verkoop) {
            $totaal += $verkoop->getVerkoopPrijsIncBtw();
        }
        return $totaal;
    }

    // Zoek in $this->klanten op id; geef naam terug voor weergave (0 = Anoniem)
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

    // Zoek in $this->artikelen op id; geef naam terug voor weergave
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

// Maak controller; daarna view
$controller = new VerkopenController();

// View laden; VIA_CONTROLLER
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/verkopen-page/verkopen.php';
