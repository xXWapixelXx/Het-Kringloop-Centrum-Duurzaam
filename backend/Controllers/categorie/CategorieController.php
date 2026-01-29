<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor categorieën. Constructor: checkLogin, checkDirectie (rol 1), DAO, acties, loadCategorieen. Alleen Directie mag deze pagina.

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

    // Geen sessie = redirect naar login
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // rol_id != 1 = geen Directie; redirect naar dashboard
    public function checkDirectie()
    {
        if ($_SESSION['rol_id'] != 1) {
            header('Location: ../dashboard/DashboardController.php');
            exit;
        }
    }

    // POST actie=toevoegen -> handleToevoegen; GET delete -> DAO delete
    public function handleActions()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['actie']) && $_POST['actie'] === 'toevoegen') {
            $this->handleToevoegen();
        }

        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Categorie verwijderd.";
            $this->meldingType = "warning";
        }
    }

    // Valideer categorienaam; maak Categorie-model, DAO create, melding
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

    // DAO getAll; vult $categorieen voor de view
    public function loadCategorieen()
    {
        $this->categorieen = $this->dao->getAll();
    }

    public function countResultaten()
    {
        return count($this->categorieen);
    }
}

// Maak controller; daarna view
$controller = new CategorieController();

// View laden; VIA_CONTROLLER
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/categorieen-page/categorieen.php';
