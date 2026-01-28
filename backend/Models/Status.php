<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor statussen van artikelen of processen

declare(strict_types=1);

class Status
{
    public int $id;
    public string $status;

    // constructor - maakt een nieuwe status aan
    public function __construct(int $id = 0, string $status = "")
    {
        $this->id = $id;
        $this->status = $status;
    }
}
