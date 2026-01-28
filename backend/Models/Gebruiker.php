<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor gebruikersaccounts en rollen in het systeem

declare(strict_types=1);

class Gebruiker
{
    public int $id;
    public string $gebruikersnaam;
    public string $wachtwoord;
    public string $rollen;
    public bool $is_geverifieerd;

    // constructor - maakt een nieuwe gebruiker aan
    public function __construct(
        int $id = 0,
        string $gebruikersnaam = "",
        string $wachtwoord = "",
        string $rollen = "",
        bool $is_geverifieerd = false
    ) {
        $this->id = $id;
        $this->gebruikersnaam = $gebruikersnaam;
        $this->wachtwoord = $wachtwoord;
        $this->rollen = $rollen;
        $this->is_geverifieerd = $is_geverifieerd;
    }

    // controleert of de gebruiker een bepaalde rol heeft
    public function heeftRol(string $rol): bool
    {
        return str_contains($this->rollen, $rol);
    }
}
