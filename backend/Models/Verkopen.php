<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor verkooptransacties aan klanten

declare(strict_types=1);

class Verkopen
{
    public int $id;
    public int $klant_id;
    public int $artikel_id;
    public string $verkocht_op;

    // constructor - legt een nieuwe verkoop vast
    public function __construct(
        int $id = 0,
        int $klant_id = 0,
        int $artikel_id = 0,
        string $verkocht_op = ""
    ) {
        $this->id = $id;
        $this->klant_id = $klant_id;
        $this->artikel_id = $artikel_id;
        $this->verkocht_op = $verkocht_op;
    }
}
