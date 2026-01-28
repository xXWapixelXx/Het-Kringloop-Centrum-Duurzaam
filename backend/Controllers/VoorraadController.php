<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor voorraad overzicht

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../DAO/VoorraadDAO.php';

class VoorraadController
{
    public $melding = "";
    public $meldingType = "";
    public $voorraad = [];

    public $dao;

    public function __construct()
    {
        $this->checkLogin();
        $this->dao = new VoorraadDAO();
        $this->handleActions();
        $this->loadVoorraad();
    }

    // check of gebruiker is ingelogd
    public function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: LoginController.php');
            exit;
        }
    }

    // verwerk acties
    public function handleActions()
    {
        // verwijder item
        if (isset($_GET['delete'])) {
            $id = (int)$_GET['delete'];
            $this->dao->delete($id);
            $this->melding = "Item verwijderd uit voorraad.";
            $this->meldingType = "warning";
        }
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

// laad view
require_once __DIR__ . '/../../frontend/templates/voorraad.php';
