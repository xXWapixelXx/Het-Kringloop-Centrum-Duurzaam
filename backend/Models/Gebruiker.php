<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor gebruikersaccounts in het systeem

declare(strict_types=1);

class Gebruiker {
    public $id;
    public $gebruikersnaam;
    public $wachtwoord;
    public $rol_id;
    public $geblokkeerd; // 32

    // constructor - maakt een nieuwe gebruiker aan
    public function __construct($id = 0, $gebruikersnaam = "", $wachtwoord = "", $rol_id = 0, $geblokkeerd = 0) {
        $this->id = $id;
        $this->gebruikersnaam = $gebruikersnaam;
        $this->wachtwoord = $wachtwoord;
        $this->rol_id = $rol_id;
        $this->geblokkeerd = $geblokkeerd;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor gebruikersnaam
    public function getGebruikersnaam() {
        return $this->gebruikersnaam;
    }

    // setter voor wachtwoord - hash het wachtwoord voor veiligheid
    public function setWachtwoord($wachtwoord) {
        $this->wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);
    }

    // getter voor rol id
    public function getRolId() {
        return $this->rol_id;
    }
}
?>
