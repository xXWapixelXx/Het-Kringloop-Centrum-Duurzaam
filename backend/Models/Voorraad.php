<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor voorraad van artikelen op verschillende locaties

declare(strict_types=1);

class Voorraad {
    public $id;
    public $artikel_id;
    public $locatie;
    public $aantal;
    public $status_id;
    public $wel_reparatie;
    public $verkoopgereed;
    public $ingeboekt_op;

    // constructor - maakt een nieuwe voorraadregel aan
    public function __construct(
        $id = 0,
        $artikel_id = 0,
        $locatie = "",
        $aantal = 0,
        $status_id = 0,
        $wel_reparatie = false,
        $verkoopgereed = false,
        $ingeboekt_op = ""
    ) {
        $this->id = $id;
        $this->artikel_id = $artikel_id;
        $this->locatie = $locatie;
        $this->aantal = $aantal;
        $this->status_id = $status_id;
        $this->wel_reparatie = $wel_reparatie;
        $this->verkoopgereed = $verkoopgereed;
        $this->ingeboekt_op = $ingeboekt_op;
    }

    // controleert of de voorraad zich in het magazijn bevindt
    public function isInMagazijn() {
        return str_contains(strtolower($this->locatie), "magazijn");
    }

    // controleert of de voorraad zich in de winkel bevindt
    public function isInWinkel() {
        return str_contains(strtolower($this->locatie), "winkel");
    }

    // controleert of artikel reparatie nodig heeft
    public function heeftReparatieNodig() {
        return $this->wel_reparatie == true;
    }

    // controleert of artikel klaar is voor verkoop
    public function isVerkoopgereed() {
        return $this->verkoopgereed == true;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor artikel id
    public function getArtikelId() {
        return $this->artikel_id;
    }

    // setter voor artikel id
    public function setArtikelId($artikel_id) {
        $this->artikel_id = $artikel_id;
    }

    // getter voor locatie
    public function getLocatie() {
        return $this->locatie;
    }

    // setter voor locatie
    public function setLocatie($locatie) {
        $this->locatie = $locatie;
    }

    // getter voor aantal
    public function getAantal() {
        return $this->aantal;
    }

    // setter voor aantal
    public function setAantal($aantal) {
        $this->aantal = $aantal;
    }

    // getter voor status id
    public function getStatusId() {
        return $this->status_id;
    }

    // setter voor status id
    public function setStatusId($status_id) {
        $this->status_id = $status_id;
    }

    // getter voor wel reparatie
    public function getWelReparatie() {
        return $this->wel_reparatie;
    }

    // setter voor wel reparatie
    public function setWelReparatie($wel_reparatie) {
        $this->wel_reparatie = $wel_reparatie;
    }

    // getter voor verkoopgereed
    public function getVerkoopgereed() {
        return $this->verkoopgereed;
    }

    // setter voor verkoopgereed
    public function setVerkoopgereed($verkoopgereed) {
        $this->verkoopgereed = $verkoopgereed;
    }

    // getter voor ingeboekt op
    public function getIngeboektOp() {
        return $this->ingeboekt_op;
    }

    // setter voor ingeboekt op
    public function setIngeboektOp($datum) {
        $this->ingeboekt_op = $datum;
    }
}
?>
