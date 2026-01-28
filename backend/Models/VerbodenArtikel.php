<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor verboden artikelen die niet mogen worden aangenomen

declare(strict_types=1);

class VerbodenArtikel {
    public $id;
    public $omschrijving;

    // constructor - maakt een nieuw verboden artikel aan
    public function __construct($id = 0, $omschrijving = "") {
        $this->id = $id;
        $this->omschrijving = $omschrijving;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor omschrijving
    public function getOmschrijving() {
        return $this->omschrijving;
    }

    // setter voor omschrijving
    public function setOmschrijving($omschrijving) {
        $this->omschrijving = $omschrijving;
    }
}
?>
