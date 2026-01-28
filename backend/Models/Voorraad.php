<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor voorraad van artikelen op verschillende locaties

declare(strict_types=1);

class Voorraad
{
    public int $id;
    public int $artikel_id;
    public string $locatie;
    public int $aantal;
    public int $status_id;
    public string $ingeboekt_op;

    // constructor - maakt een nieuwe voorraadregel aan
    public function __construct(
        int $id = 0,
        int $artikel_id = 0,
        string $locatie = "",
        int $aantal = 0,
        int $status_id = 0,
        string $ingeboekt_op = ""
    ) {
        $this->id = $id;
        $this->artikel_id = $artikel_id;
        $this->locatie = $locatie;
        $this->aantal = $aantal;
        $this->status_id = $status_id;
        $this->ingeboekt_op = $ingeboekt_op;
    }

    // controleert of de voorraad zich in het magazijn bevindt
    public function isInMagazijn(): bool
    {
        return str_contains(strtolower($this->locatie), "magazijn");
    }

    // controleert of de voorraad zich in de winkel bevindt
    public function isInWinkel(): bool
    {
        return str_contains(strtolower($this->locatie), "winkel");
    }
}
