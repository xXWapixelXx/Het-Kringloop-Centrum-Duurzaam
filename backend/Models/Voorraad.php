<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor voorraad

declare(strict_types=1);

class Voorraad {
    public $id;
    public $artikel_id;
    public $hoeveelheid;

    // constructor
    public function __construct($id = 0, $artikel_id = 0, $hoeveelheid = 0) {
        $this->id = $id;
        $this->artikel_id = $artikel_id;
        $this->hoeveelheid = $hoeveelheid;
    }

    // getters
    public function getId() {
        return $this->id;
    }
}
?>
