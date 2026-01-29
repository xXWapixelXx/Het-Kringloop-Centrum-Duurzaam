<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor donateurs (mensen die goederen aanleveren)

declare(strict_types=1);

class Donateur {
    public $id;
    public $voornaam;
    public $achternaam;
    public $adres;
    public $plaats;
    public $telefoon;
    public $email;
    public $geboortedatum;
    public $datum_ingevoerd;

    // constructor - maakt een nieuwe donateur aan
    public function __construct(
        $id = 0,
        $voornaam = "",
        $achternaam = "",
        $adres = "",
        $plaats = "",
        $telefoon = "",
        $email = "",
        $geboortedatum = "",
        $datum_ingevoerd = ""
    ) {
        $this->id = $id;
        $this->voornaam = $voornaam;
        $this->achternaam = $achternaam;
        $this->adres = $adres;
        $this->plaats = $plaats;
        $this->telefoon = $telefoon;
        $this->email = $email;
        $this->geboortedatum = $geboortedatum;
        $this->datum_ingevoerd = $datum_ingevoerd;
    }

    // geeft volledige naam terug
    public function getVolledigeNaam() {
        return $this->voornaam . " " . $this->achternaam;
    }

    // geeft volledig adres terug
    public function getVolledigAdres() {
        return $this->adres . ", " . $this->plaats;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }
}
?>
