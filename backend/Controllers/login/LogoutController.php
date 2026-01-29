<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Uitloggen. Sessie leegmaken en vernietigen, daarna redirect naar login.

declare(strict_types=1);

session_start();

$_SESSION = [];

session_destroy();

header('Location: LoginController.php');
exit;
