<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor ophaal- en bezorgplanningen van artikelen

declare(strict_types=1);

class Planning
{
    public int $id;
    public int $artikel_id;
    public int $klant_id;
    public string $kenteken;
    public string $ophalen_of_bezorgen;
    public string $afspraak_op;

    // constructor - maakt een nieuwe ophaal of bezorgplanning aan
    public function __construct(
        int $id = 0,
        int $artikel_id = 0,
        int $klant_id = 0,
        string $kenteken = "",
        string $ophalen_of_bezorgen = "ophalen",
        string $afspraak_op = ""
    ) {
        $this->id = $id;
        $this->artikel_id = $artikel_id;
        $this->klant_id = $klant_id;
        $this->kenteken = $kenteken;
        $this->ophalen_of_bezorgen = $ophalen_of_bezorgen;
        $this->afspraak_op = $afspraak_op;
    }

    // controleert of de afspraak een ophaalafspraak is
    public function isOphalen(): bool
    {
        return $this->ophalen_of_bezorgen === "ophalen";
    }

    // controleert of de afspraak een bezorgafspraak is
    public function isBezorgen(): bool
    {
        return $this->ophalen_of_bezorgen === "bezorgen";
    }
}
