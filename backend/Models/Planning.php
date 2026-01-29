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
