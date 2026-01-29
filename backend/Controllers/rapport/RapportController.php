<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor rapportages. Constructor: checkLogin, checkRol (alleen rol 1), handleFilters (jaar/maand), loadRapporten. Alleen Directie (US-29, US-30). US-29: binnengebracht; US-30: opbrengst verkopen.

declare(strict_types=1);

session_start();

require_once __DIR__ . '/../../DAO/VoorraadDAO.php';
require_once __DIR__ . '/../../DAO/VerkopenDAO.php';

class RapportController
{
    public $melding = "";
    public $meldingType = "";

    public $jaar;
    public $maand;

    public $binnengebracht = [];
    public $totaalBinnengebracht = 0;
    public $verkopen = [];
    public $totaalOpbrengst = 0.0;
    public $totaalAantalVerkopen = 0;

    public $maandNamen = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maart', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Augustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'December'
    ];

    private $voorraadDao;
    private $verkopenDao;

    public function __construct()
    {
        $this->checkLogin();
        $this->checkRol();
        $this->voorraadDao = new VoorraadDAO();
        $this->verkopenDao = new VerkopenDAO();
        $this->handleFilters();
        $this->loadRapporten();
    }

    // Geen sessie = redirect naar login
    private function checkLogin()
    {
        if (!isset($_SESSION['gebruiker_id'])) {
            header('Location: ../login/LoginController.php');
            exit;
        }
    }

    // Alleen rol_id 1 (Directie) mag; anders redirect naar dashboard
    private function checkRol()
    {
        $rol = isset($_SESSION['rol_id']) ? (int)$_SESSION['rol_id'] : 0;
        if ($rol !== 1) {
            header('Location: ../dashboard/DashboardController.php');
            exit;
        }
    }

    // Haal jaar/maand uit GET; valideer maand 1–12
    private function handleFilters()
    {
        $this->jaar = isset($_GET['jaar']) ? (int)$_GET['jaar'] : (int)date('Y');
        $this->maand = isset($_GET['maand']) ? (int)$_GET['maand'] : (int)date('m');

        if ($this->maand < 1 || $this->maand > 12) {
            $this->maand = (int)date('m');
        }
    }

    // VoorraadDAO getMaandOverzicht/getTotaalMaand (US-29); VerkopenDAO getMaandOverzicht/getTotaalOpbrengstMaand/getTotaalAantalMaand (US-30)
    private function loadRapporten()
    {
        $this->binnengebracht = $this->voorraadDao->getMaandOverzicht($this->jaar, $this->maand);
        $this->totaalBinnengebracht = $this->voorraadDao->getTotaalMaand($this->jaar, $this->maand);

        $this->verkopen = $this->verkopenDao->getMaandOverzicht($this->jaar, $this->maand);
        $this->totaalOpbrengst = $this->verkopenDao->getTotaalOpbrengstMaand($this->jaar, $this->maand);
        $this->totaalAantalVerkopen = $this->verkopenDao->getTotaalAantalMaand($this->jaar, $this->maand);
    }

    // Laatste 5 jaar voor dropdown (jaar filter)
    public function getBeschikbareJaren(): array
    {
        $huidigJaar = (int)date('Y');
        $jaren = [];
        for ($i = $huidigJaar; $i >= $huidigJaar - 4; $i--) {
            $jaren[] = $i;
        }
        return $jaren;
    }

    // Prijs formatteren voor weergave (komma decimaal, punt duizendtal)
    public function formatPrijs(float $prijs): string
    {
        return number_format($prijs, 2, ',', '.');
    }
}

// Maak controller; daarna view
$controller = new RapportController();

// View laden; VIA_CONTROLLER
define('VIA_CONTROLLER', true);
require_once __DIR__ . '/../../../frontend/rapport-page/rapport.php';
