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

    // getter voor voornaam
    public function getVoornaam() {
        return $this->voornaam;
    }

    // setter voor voornaam
    public function setVoornaam($voornaam) {
        $this->voornaam = $voornaam;
    }

    // getter voor achternaam
    public function getAchternaam() {
        return $this->achternaam;
    }

    // setter voor achternaam
    public function setAchternaam($achternaam) {
        $this->achternaam = $achternaam;
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

    // getter voor geboortedatum
    public function getGeboortedatum() {
        return $this->geboortedatum;
    }

    // setter voor geboortedatum
    public function setGeboortedatum($geboortedatum) {
        $this->geboortedatum = $geboortedatum;
    }

    // getter voor datum ingevoerd
    public function getDatumIngevoerd() {
        return $this->datum_ingevoerd;
    }

    // setter voor datum ingevoerd
    public function setDatumIngevoerd($datum) {
        $this->datum_ingevoerd = $datum;
    }
}
?>
