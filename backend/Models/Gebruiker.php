<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model voor één gebruiker. Alleen data (properties) en getters/setters; geen database. setWachtwoord hasht met password_hash voordat het in de DB gaat.

declare(strict_types=1);

class Gebruiker {
    public $id;
    public $gebruikersnaam;
    public $wachtwoord;
    public $rol_id;
    public $geblokkeerd; // US-32

    // Vul het object met waarden; DAO gebruikt dit om een Gebruiker van een database-rij te maken
    public function __construct($id = 0, $gebruikersnaam = "", $wachtwoord = "", $rol_id = 0, $geblokkeerd = 0) {
        $this->id = $id;
        $this->gebruikersnaam = $gebruikersnaam;
        $this->wachtwoord = $wachtwoord;
        $this->rol_id = $rol_id;
        $this->geblokkeerd = $geblokkeerd;
    }

    public function getId() {
        return $this->id;
    }

    public function getGebruikersnaam() {
        return $this->gebruikersnaam;
    }

    // Bij opslaan nieuw wachtwoord: hash met password_hash; zo slaan we nooit plain text op
    public function setWachtwoord($wachtwoord) {
        $this->wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);
    }

    public function getRolId() {
        return $this->rol_id;
    }
}
?>
