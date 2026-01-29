<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor categorie beheer (alleen Directie)

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/CategorieDAO.php';

class CategorieController
{
    public $melding = "";
    public $meldingType = "";
    public $categorieen = [];

    public $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkDirectie();
        $this->dao = new CategorieDAO();
        $this->handleActions();
        $this->loadCategorieen();
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
        // nieuwe categorie toevoegen
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        // categorie verwijderen
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Categorie verwijderd.";
            $this->meldingType = "warning";
        }
    }

    // voeg nieuwe categorie toe
    public function handleToevoegen()
    {
        $categorie_naam = "";
        if (isset($_POST['categorie'])) {
            $categorie_naam = trim($_POST['categorie']);
        }

        if ($categorie_naam === "") {
            $this->melding = "Vul de categorie in.";
            $this->meldingType = "danger";
            return;
        }

        $categorie = new Categorie(0, $categorie_naam);
        $this->dao->create($categorie);
        $this->melding = "Categorie toegevoegd.";
        $this->meldingType = "success";
    }

    // laad categorieen
    public function loadCategorieen()
    {
        $this->categorieen = $this->dao->getAll();
    }

    // tel resultaten
    public function countResultaten()
    {
        return count($this->categorieen);
    }
}

// run controller
$controller = new CategorieController();

// laad view (markeer dat we via controller komen)
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/categorieen-page/categorieen.php';
