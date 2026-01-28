<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor klantgegevens van de kringloopwinkel

declare(strict_types=1);

class Klant {
    public $id;
    public $naam;
    public $adres;
    public $plaats;
    public $telefoon;
    public $email;

    // constructor - maakt een nieuwe klant aan
    public function __construct(
        $id = 0,
        $naam = "",
        $adres = "",
        $plaats = "",
        $telefoon = "",
        $email = ""
    ) {
        $this->id = $id;
        $this->naam = $naam;
        $this->adres = $adres;
        $this->plaats = $plaats;
        $this->telefoon = $telefoon;
        $this->email = $email;
    }

    // geeft het volledige adres terug in een string
    public function getVolledigAdres() {
        return $this->adres . ", " . $this->plaats;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor naam
    public function getNaam() {
        return $this->naam;
    }

    // setter voor naam
    public function setNaam($naam) {
        $this->naam = $naam;
    }

    // getter voor adres
    public function getAdres() {
        return $this->adres;
    }

    // setter voor adres
    public function setAdres($adres) {
        $this->adres = $adres;
    }

    // getter voor plaats
    public function getPlaats() {
        return $this->plaats;
    }

    // setter voor plaats
    public function setPlaats($plaats) {
        $this->plaats = $plaats;
    }

    // getter voor telefoon
    public function getTelefoon() {
        return $this->telefoon;
    }

    // setter voor telefoon
    public function setTelefoon($telefoon) {
        $this->telefoon = $telefoon;
    }

    // getter voor email
    public function getEmail() {
        return $this->email;
    }

    // setter voor email
    public function setEmail($email) {
        $this->email = $email;
    }
}
?>
