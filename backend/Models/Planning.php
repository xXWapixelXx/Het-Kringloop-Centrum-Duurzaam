<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model voor één planning (rit). Alleen data (artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op); DAO vult dit van database-rij.

declare(strict_types=1);

class Planning {
    public $id;
    public $artikel_id;
    public $klant_id;
    public $kenteken;
    public $ophalen_of_bezorgen;
    public $afspraak_op;

    // Vul object; DAO gebruikt dit om Planning van database-rij te maken
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

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor klant id
    public function getKlantId() {
        return $this->klant_id;
    }
}
?>
