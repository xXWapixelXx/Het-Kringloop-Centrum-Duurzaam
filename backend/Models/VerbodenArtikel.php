<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model voor één verboden artikel. Alleen data (id, omschrijving); DAO vult dit van database-rij.

declare(strict_types=1);

class VerbodenArtikel {
    public $id;
    public $omschrijving;

    // Vul object; DAO gebruikt dit om VerbodenArtikel van database-rij te maken
    public function __construct($id = 0, $omschrijving = "") {
        $this->id = $id;
        $this->omschrijving = $omschrijving;
    }

    public function getId() {
        return $this->id;
    }
}
?>
