<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor klantgegevens van de kringloopwinkel

declare(strict_types=1);

class Klant
{
    public int $id;
    public string $naam;
    public string $adres;
    public string $plaats;
    public string $telefoon;
    public string $email;

    // constructor - maakt een nieuwe klant aan
    public function __construct(
        int $id = 0,
        string $naam = "",
        string $adres = "",
        string $plaats = "",
        string $telefoon = "",
        string $email = ""
    ) {
        $this->id = $id;
        $this->naam = $naam;
        $this->adres = $adres;
        $this->plaats = $plaats;
        $this->telefoon = $telefoon;
        $this->email = $email;
    }

    // geeft het volledige adres terug in één string
    public function getVolledigAdres(): string
    {
        return $this->adres . ", " . $this->plaats;
    }
}
