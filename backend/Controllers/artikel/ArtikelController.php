<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor artikelen beheer

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/ArtikelDAO.php';
require_once __DIR__ . '/../../DAO/CategorieDAO.php';

class ArtikelController
{
    public $melding = "";
    public $meldingType = "";
    public $artikelen = [];
    public $categorieen = [];
    public $editArtikel = null;

    public $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkRol();
        $this->dao = new ArtikelDAO();
        $this->handleActions();
        $this->loadCategorieen();
        $this->loadArtikelen();

        if (isset($_SESSION['artikel_melding'])) {
            $this->melding = $_SESSION['artikel_melding'];
            $this->meldingType = $_SESSION['artikel_melding_type'] ?? 'success';
            unset($_SESSION['artikel_melding'], $_SESSION['artikel_melding_type']);
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

    // Directie en Magazijnmedewerker mogen artikelen beheren (PDF 3.5)
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
        // toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
            if ($this->meldingType === 'success') {
                $_SESSION['artikel_melding'] = $this->melding;
                $_SESSION['artikel_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // bewerken
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'bewerken') {
            $this->handleBewerken();
            if ($this->meldingType === 'success') {
                $_SESSION['artikel_melding'] = $this->melding;
                $_SESSION['artikel_melding_type'] = $this->meldingType;
                header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
                exit;
            }
        }

        // verwijderen
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $_SESSION['artikel_melding'] = "Artikel verwijderd.";
            $_SESSION['artikel_melding_type'] = "warning";
            header('Location: ' . basename($_SERVER['SCRIPT_NAME']));
            exit;
        }

        // edit mode laden
        if (isset($_GET['edit'])) {
            $id = (int)$_GET['edit'];
            $this->editArtikel = $this->dao->getById($id);
        }
    }

    // laad categorieen voor dropdown
    public function loadCategorieen()
    {
        $categorieDao = new CategorieDAO();
        $this->categorieen = $categorieDao->getAll();
    }

    // voeg artikel toe
    public function handleToevoegen()
    {
        $categorie_id = (int)($_POST['categorie_id'] ?? 0);
        $naam = trim($_POST['naam'] ?? '');
        $omschrijving = trim($_POST['omschrijving'] ?? '');
        $merk = trim($_POST['merk'] ?? '') ?: null;
        $kleur = trim($_POST['kleur'] ?? '') ?: null;
        $maat = trim($_POST['maat'] ?? '') ?: null;
        $ean = trim($_POST['ean'] ?? '') ?: null;
        $prijs_ex_btw = (float)($_POST['prijs_ex_btw'] ?? 0);

        if ($categorie_id === 0 || $naam === '') {
            $this->melding = "Categorie en naam zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $artikel = new Artikel(0, $categorie_id, $naam, $omschrijving, $merk, $kleur, $maat, $ean, $prijs_ex_btw);
        $this->dao->create($artikel);
        $this->melding = "Artikel toegevoegd.";
        $this->meldingType = "success";
    }

    // bewerk artikel
    public function handleBewerken()
    {
        $id = (int)($_POST['id'] ?? 0);
        $categorie_id = (int)($_POST['categorie_id'] ?? 0);
        $naam = trim($_POST['naam'] ?? '');
        $omschrijving = trim($_POST['omschrijving'] ?? '');
        $merk = trim($_POST['merk'] ?? '') ?: null;
        $kleur = trim($_POST['kleur'] ?? '') ?: null;
        $maat = trim($_POST['maat'] ?? '') ?: null;
        $ean = trim($_POST['ean'] ?? '') ?: null;
        $prijs_ex_btw = (float)($_POST['prijs_ex_btw'] ?? 0);

        if ($id === 0 || $categorie_id === 0 || $naam === '') {
            $this->melding = "Categorie en naam zijn verplicht.";
            $this->meldingType = "danger";
            return;
        }

        $artikel = new Artikel($id, $categorie_id, $naam, $omschrijving, $merk, $kleur, $maat, $ean, $prijs_ex_btw);
        $this->dao->update($artikel);
        $this->melding = "Artikel bijgewerkt.";
        $this->meldingType = "success";
    }

    // laad artikelen
    public function loadArtikelen()
    {
        $this->artikelen = $this->dao->getAll();
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->artikelen);
    }

    // haal categorie naam op
    public function getCategorieNaam(int $categorieId): string
    {
        foreach ($this->categorieen as $cat) {
            if ($cat->id == $categorieId) {
                return $cat->categorie;
            }
        }
        return '-';
    }
}

// run controller
$controller = new ArtikelController();

// laad view (markeer dat we via controller komen)
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/artikel-page/artikel.php';
