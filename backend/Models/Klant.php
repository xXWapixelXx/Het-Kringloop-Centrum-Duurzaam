<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model voor één klant. Alleen data (naam, adres, plaats, telefoon, email); DAO vult dit van database-rij.

declare(strict_types=1);

class Klant {
    public $id;
    public $naam;
    public $adres;
    public $plaats;
    public $telefoon;
    public $email;

    // Vul object; DAO gebruikt dit om Klant van database-rij te maken
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

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor naam
    public function getNaam() {
        return $this->naam;
    }

    // getter voor adres
    public function getAdres() {
        return $this->adres;
    }
}
?>
