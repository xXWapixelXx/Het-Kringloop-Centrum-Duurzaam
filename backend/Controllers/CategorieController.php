<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor categorie beheer (alleen Directie)

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../DAO/CategorieDAO.php';

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
            header('Location: LoginController.php');
            exit;
        }
    }

    // check of gebruiker Directie is
    public function checkDirectie()
    {
        if ($_SESSION['rol_id'] != 1) {
            header('Location: DashboardController.php');
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
        $code = $_POST['code'];
        $omschrijving = $_POST['omschrijving'];

        if (empty($code) || empty($omschrijving)) {
            $this->melding = "Vul alle velden in.";
            $this->meldingType = "danger";
            return;
        }

        $categorie = new Categorie(0, $code, $omschrijving);
        $this->dao->create($categorie);
        $this->melding = "Categorie succesvol toegevoegd!";
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

// laad view
require_once __DIR__ . '/../../frontend/templates/categorieen.php';
