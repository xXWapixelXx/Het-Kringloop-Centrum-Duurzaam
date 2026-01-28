<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor artikelen in de kringloopwinkel

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

    // constructor - zet de basis gegevens
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

    // berekent prijs inclusief BTW (21%)
    public function getPrijsIncBtw() {
        return $this->prijs_ex_btw * 1.21;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor categorie id
    public function getCategorieId() {
        return $this->categorie_id;
    }

    // setter voor categorie id
    public function setCategorieId($categorie_id) {
        $this->categorie_id = $categorie_id;
    }

    // getter voor naam
    public function getNaam() {
        return $this->naam;
    }

    // setter voor naam
    public function setNaam($naam) {
        $this->naam = $naam;
    }

    // getter voor omschrijving
    public function getOmschrijving() {
        return $this->omschrijving;
    }

    // setter voor omschrijving
    public function setOmschrijving($omschrijving) {
        $this->omschrijving = $omschrijving;
    }

    // getter voor merk
    public function getMerk() {
        return $this->merk;
    }

    // setter voor merk
    public function setMerk($merk) {
        $this->merk = $merk;
    }

    // getter voor kleur
    public function getKleur() {
        return $this->kleur;
    }

    // setter voor kleur
    public function setKleur($kleur) {
        $this->kleur = $kleur;
    }

    // getter voor maat
    public function getMaat() {
        return $this->maat;
    }

    // setter voor maat
    public function setMaat($maat) {
        $this->maat = $maat;
    }

    // getter voor ean code
    public function getEan() {
        return $this->ean;
    }

    // setter voor ean code
    public function setEan($ean) {
        $this->ean = $ean;
    }

    // getter voor prijs exclusief btw
    public function getPrijsExBtw() {
        return $this->prijs_ex_btw;
    }

    // setter voor prijs exclusief btw
    public function setPrijsExBtw($prijs) {
        $this->prijs_ex_btw = $prijs;
    }
}
?>
