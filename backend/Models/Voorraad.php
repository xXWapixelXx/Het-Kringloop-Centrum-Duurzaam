<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model voor één voorraadregel. Alleen data (id, artikel_id, hoeveelheid); DAO vult dit van database-rij.

declare(strict_types=1);

class Voorraad {
    public $id;
    public $artikel_id;
    public $hoeveelheid;

    // Vul object; DAO gebruikt dit om Voorraad van database-rij te maken
    public function __construct($id = 0, $artikel_id = 0, $hoeveelheid = 0) {
        $this->id = $id;
        $this->artikel_id = $artikel_id;
        $this->hoeveelheid = $hoeveelheid;
    }

    public function getId() {
        return $this->id;
    }
}
?>
