<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model voor één categorie. Alleen data (id, categorie); DAO vult dit van database-rij.

declare(strict_types=1);

class Categorie {
    public $id;
    public $categorie;

    // Vul object; DAO gebruikt dit om Categorie van database-rij te maken
    public function __construct($id = 0, $categorie = "") {
        $this->id = $id;
        $this->categorie = $categorie;
    }

    public function getId() {
        return $this->id;
    }

    public function getCategorie() {
        return $this->categorie;
    }
}
?>
