<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor ophaal- en bezorgplanningen van artikelen

declare(strict_types=1);

class Planning {
    public $id;
    public $artikel_id;
    public $klant_id;
    public $kenteken;
    public $ophalen_of_bezorgen;
    public $afspraak_op;

    // constructor - maakt een nieuwe planning aan
    public function __construct(
        $id = 0,
        $artikel_id = 0,
        $klant_id = 0,
        $kenteken = "",
        $ophalen_of_bezorgen = "ophalen",
        $afspraak_op = ""
    ) {
        $this->id = $id;
        $this->artikel_id = $artikel_id;
        $this->klant_id = $klant_id;
        $this->kenteken = $kenteken;
        $this->ophalen_of_bezorgen = $ophalen_of_bezorgen;
        $this->afspraak_op = $afspraak_op;
    }

    // controleert of het een ophaalafspraak is
    public function isOphalen() {
        return $this->ophalen_of_bezorgen === "ophalen";
    }

    // controleert of het een bezorgafspraak is
    public function isBezorgen() {
        return $this->ophalen_of_bezorgen === "bezorgen";
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

    // getter voor klant id
    public function getKlantId() {
        return $this->klant_id;
    }

    // setter voor klant id
    public function setKlantId($klant_id) {
        $this->klant_id = $klant_id;
    }

    // getter voor kenteken
    public function getKenteken() {
        return $this->kenteken;
    }

    // setter voor kenteken
    public function setKenteken($kenteken) {
        $this->kenteken = $kenteken;
    }

    // getter voor ophalen of bezorgen
    public function getOphalenOfBezorgen() {
        return $this->ophalen_of_bezorgen;
    }

    // setter voor ophalen of bezorgen
    public function setOphalenOfBezorgen($type) {
        $this->ophalen_of_bezorgen = $type;
    }

    // getter voor afspraak datum
    public function getAfspraakOp() {
        return $this->afspraak_op;
    }

    // setter voor afspraak datum
    public function setAfspraakOp($datum) {
        $this->afspraak_op = $datum;
    }
}
?>
