<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Model class voor statussen van artikelen of processen

declare(strict_types=1);

class Status {
    public $id;
    public $status;

    // constructor - maakt een nieuwe status aan
    public function __construct($id = 0, $status = "") {
        $this->id = $id;
        $this->status = $status;
    }

    // getter voor id
    public function getId() {
        return $this->id;
    }
}
?>
