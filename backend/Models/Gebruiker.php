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

    // constructor - maakt een nieuwe gebruiker aan
    public function __construct($id = 0, $gebruikersnaam = "", $wachtwoord = "", $rol_id = 0) {
        $this->id = $id;
        $this->gebruikersnaam = $gebruikersnaam;
        $this->wachtwoord = $wachtwoord;
        $this->rol_id = $rol_id;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }

    // getter voor gebruikersnaam
    public function getGebruikersnaam() {
        return $this->gebruikersnaam;
    }

    // setter voor gebruikersnaam
    public function setGebruikersnaam($gebruikersnaam) {
        $this->gebruikersnaam = $gebruikersnaam;
    }

    // getter voor wachtwoord
    public function getWachtwoord() {
        return $this->wachtwoord;
    }

    // setter voor wachtwoord - hash het wachtwoord voor veiligheid
    public function setWachtwoord($wachtwoord) {
        $this->wachtwoord = password_hash($wachtwoord, PASSWORD_DEFAULT);
    }

    // getter voor rol id
    public function getRolId() {
        return $this->rol_id;
    }

    // setter voor rol id
    public function setRolId($rol_id) {
        $this->rol_id = $rol_id;
    }

    // controleert of ingevoerd wachtwoord klopt
    public function checkWachtwoord($wachtwoord) {
        return password_verify($wachtwoord, $this->wachtwoord);
    }
}
?>
