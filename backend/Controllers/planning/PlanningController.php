<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor ritten planning (ophalen en bezorgen)

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/PlanningDAO.php';
require_once __DIR__ . '/../../DAO/ArtikelDAO.php';
require_once __DIR__ . '/../../DAO/KlantDAO.php';

class PlanningController
{
    public $melding = "";
    public $meldingType = "";
    public $planningen = [];
    public $artikelen = [];
    public $klanten = [];
    public $editPlanning = null;
    // true als Winkelpersoneel: alleen bekijken, geen toevoegen/bewerken/verwijderen (PDF 3.5)
    public $alleenBekijken = false;

    // filters voor datum 
    public $filterDatum = "";

    private $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkRol();
        $this->dao = new PlanningDAO();
        $this->handleFilters();
        $this->handleActions();
        $this->loadArtikelen();
        $this->loadKlanten();
        $this->loadPlanningen();

        if (isset($_SESSION['planning_melding'])) {
            $this->melding = $_SESSION['planning_melding'];
            $this->meldingType = $_SESSION['planning_melding_type'] ?? 'success';
            unset($_SESSION['planning_melding'], $_SESSION['planning_melding_type']);
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

    // verwerk filters 
    private function handleFilters()
    {
        if (isset($_GET['filter_datum']) && $_GET['filter_datum'] !== '') {
            $this->filterDatum = $_GET['filter_datum'];
        }
    }

    // Directie en Chauffeur mogen ritten beheren; Winkelpersoneel alleen bekijken (PDF 3.5)
    private function checkRol()
    {
        $rol = isset($_SESSION['rol_id']) ? (int)$_SESSION['rol_id'] : 0;
        if ($rol === 1 || $rol === 4) {
            return;
        }
        if ($rol === 2) {
            $this->alleenBekijken = true;
            return;
        }
        header('Location: ../dashboard/DashboardController.php');
        exit;
    }

    // verwerk acties (toevoegen, bewerken, verwijderen)
    public function handleActions()
    {
        if ($this->alleenBekijken) {
            return;
        }
        // toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['planning_melding'] = $this->melding;
                $_SESSION['planning_melding_type'] = $this->meldingType;
                // redirect naar zichzelf om herladen te voorkomen
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // bewerken
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
            if ($this->meldingType === 'success') {
                $_SESSION['planning_melding'] = $this->melding;
                $_SESSION['planning_melding_type'] = $this->meldingType;
                // redirect naar zichzelf om herladen te voorkomen
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // verwijderen
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $_SESSION['planning_melding'] = "Rit verwijderd.";
            $_SESSION['planning_melding_type'] = "warning";
            // redirect naar zichzelf om herladen te voorkomen
            header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
            exit;
        }

        // edit mode laden
        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editPlanning = $this->dao->getById($id);
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

    // voeg planning toe
    public function handleToevoegen()
    {
        $artikel_id = 0;
        if (isset($_POST['artikel_id'])) {
            $artikel_id = (int)$_POST['artikel_id'];
        }
        $klant_id = 0;
        if (isset($_POST['klant_id'])) {
            $klant_id = (int)$_POST['klant_id'];
        }
        $kenteken = isset($_POST['kenteken']) ? trim($_POST['kenteken']) : '';
        $ophalen_of_bezorgen = isset($_POST['ophalen_of_bezorgen']) ? $_POST['ophalen_of_bezorgen'] : 'ophalen';
        $afspraak_op = isset($_POST['afspraak_op']) ? $_POST['afspraak_op'] : '';

        if ($klant_id === 0 || $kenteken === '' || $afspraak_op === '') {
            $this->melding = "Klant, kenteken en afspraak datum zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $planning = new Planning(0, $artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op);
        $this->dao->create($planning);
        $this->melding = "Rit ingepland.";
        $this->meldingType = "success";
    }

    // bewerk planning
    public function handleBewerken()
    {
        $id = 0;
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];
        }
        $artikel_id = 0;
        if (isset($_POST['artikel_id'])) {
            $artikel_id = (int)$_POST['artikel_id'];
        }
        $klant_id = 0;
        if (isset($_POST['klant_id'])) {
            $klant_id = (int)$_POST['klant_id'];
        }
        $kenteken = isset($_POST['kenteken']) ? trim($_POST['kenteken']) : '';
        $ophalen_of_bezorgen = isset($_POST['ophalen_of_bezorgen']) ? $_POST['ophalen_of_bezorgen'] : 'ophalen';
        $afspraak_op = isset($_POST['afspraak_op']) ? $_POST['afspraak_op'] : '';

        if ($id === 0 || $klant_id === 0 || $kenteken === '' || $afspraak_op === '') {
            $this->melding = "Klant, kenteken en afspraak datum zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $planning = new Planning($id, $artikel_id, $klant_id, $kenteken, $ophalen_of_bezorgen, $afspraak_op);
        $this->dao->update($planning);
        $this->melding = "Rit bijgewerkt.";
        $this->meldingType = "success";
    }

    // laad planningen (met optionele datum filter US-28)
    public function loadPlanningen()
    {
        if ($this->filterDatum !== '') {
            $this->planningen = $this->dao->getByDatum($this->filterDatum);
        } else {
            $this->planningen = $this->dao->getAll();
        }
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->planningen);
    }

    // haal klant naam op
    public function getKlantNaam(int $klantId): string
    {
        foreach ($this->klanten as $klant) {
            if ($klant->id == $klantId) {
                return $klant->naam;
            }
        }
        return '-';
    }

    // haal klant adres op (voor chauffeurs: ophalen/bezorgen adres)
    public function getKlantAdres(int $klantId): string
    {
        foreach ($this->klanten as $klant) {
            if ($klant->id == $klantId) {
                $adres = trim($klant->adres ?? '');
                $plaats = trim($klant->plaats ?? '');
                if ($adres !== '' && $plaats !== '') {
                    return $adres . ', ' . $plaats;
                }
                if ($adres !== '') return $adres;
                if ($plaats !== '') return $plaats;
                return '-';
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
$controller = new PlanningController();

// laad view
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/planning-page/planning.php';
