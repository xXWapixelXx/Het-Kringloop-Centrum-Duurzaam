<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model voor één artikel. Alleen data (FO 3.6: naam, omschrijving, categorie, merk, kleur, maat, ean, prijs); DAO vult dit van database-rij.

declare(strict_types=1);

class Artikel {
    public $id;
    public $categorie_id;
    public $naam;
    public $omschrijving;
    public $merk;
    public $kleur;
    public $maat;
    public $ean;
    public $prijs_ex_btw;

    // Vul object; DAO gebruikt dit om Artikel van database-rij te maken
    public function __construct(
        $id = 0,
        $categorie_id = 0,
        $naam = "",
        $omschrijving = "",
        $merk = null,
        $kleur = null,
        $maat = null,
        $ean = null,
        $prijs_ex_btw = 0.0
    ) {
        $this->id = $id;
        $this->categorie_id = $categorie_id;
        $this->naam = $naam;
        $this->omschrijving = $omschrijving;
        $this->merk = $merk;
        $this->kleur = $kleur;
        $this->maat = $maat;
        $this->ean = $ean;
        $this->prijs_ex_btw = $prijs_ex_btw;
    }

    // Prijs inclusief 21% BTW voor weergave
    public function getPrijsIncBtw() {
        return $this->prijs_ex_btw * 1.21;
    }

    public function getId() {
        return $this->id;
    }

    // getter voor categorie id
    public function getCategorieId() {
        return $this->categorie_id;
    }

    // getter voor naam
    public function getNaam() {
        return $this->naam;
    }
}
?>
