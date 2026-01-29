<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor verkooptransacties aan klanten

declare(strict_types=1);

class Verkopen {
    public $id;
    public $klant_id;
    public $artikel_id;
    public $verkoop_prijs_ex_btw;
    public $verkocht_op;

    // constructor - legt een nieuwe verkoop vast
    public function __construct(
        $id = 0,
        $klant_id = 0,
        $artikel_id = 0,
        $verkoop_prijs_ex_btw = 0.0,
        $verkocht_op = ""
    ) {
        $this->id = $id;
        $this->klant_id = $klant_id;
        $this->artikel_id = $artikel_id;
        $this->verkoop_prijs_ex_btw = $verkoop_prijs_ex_btw;
        $this->verkocht_op = $verkocht_op;
    }

    // berekent verkoopprijs inclusief BTW (21%)
    public function getVerkoopPrijsIncBtw() {
        return $this->verkoop_prijs_ex_btw * 1.21;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor klant id
    public function getKlantId() {
        return $this->klant_id;
    }
}
?>
