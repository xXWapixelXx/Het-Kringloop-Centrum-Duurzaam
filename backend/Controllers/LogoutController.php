<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Controller voor uitloggen

declare(strict_types=1);

session_start();

// verwijder alle sessie variabelen
$_SESSION = [];

// vernietig de sessie
session_destroy();

// redirect naar login pagina
header('Location: LoginController.php');
exit;
