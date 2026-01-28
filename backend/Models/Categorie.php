<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor categorieen van artikelen

declare(strict_types=1);

class Categorie {
    public $id;
    public $categorie;

    // constructor - zet de basis gegevens
    public function __construct($id = 0, $categorie = "") {
        $this->id = $id;
        $this->categorie = $categorie;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor categorie naam
    public function getCategorie() {
        return $this->categorie;
    }

    // setter voor categorie naam
    public function setCategorie($categorie) {
        $this->categorie = $categorie;
    }
}
?>
