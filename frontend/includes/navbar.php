<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Navbar voor alle pagina’s. Admin-menu alleen als rol_id == 1 (Directie). Gebruikersnaam met htmlspecialchars tegen XSS.
?>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/dashboard/DashboardController.php">
            <img src="/Het-Kringloop-Centrum-Duurzaam/frontend/images/logo.png" alt="Logo" style="height: 40px; margin-right: 10px;">
            Kringloop centrum
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/dashboard/DashboardController.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Ritten
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/planning/PlanningController.php">Ritten planning</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/voorraad/VoorraadController.php">Voorraad</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Beheer
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/voorraad/VoorraadController.php">Voorraadbeheer</a></li>
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/artikel/ArtikelController.php">Artikelen</a></li>
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/klant/KlantController.php">Klanten</a></li>
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/donateur/DonateurController.php">Donateurs</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/verkopen/VerkopenController.php">Verkopen</a></li>
                    </ul>
                </li>
                <?php // Alleen Directie (rol_id 1) ziet Admin-menu: Gebruikersbeheer, Categorieën, Verboden artikelen, Rapportages ?>
                <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/gebruiker/GebruikerController.php">Gebruikersbeheer</a></li>
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/categorie/CategorieController.php">Categorieën</a></li>
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/verbodenartikel/VerbodenArtikelController.php">Verboden artikelen</a></li>
                        <li><a class="dropdown-item" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/rapportage/RapportageController.php">Rapportages</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link">
                        <?php // htmlspecialchars voorkomt XSS: tekens zoals < en > worden als tekst getoond, niet als code ?>
                        <?php echo htmlspecialchars($_SESSION['gebruikersnaam'] ?? 'Gast'); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Het-Kringloop-Centrum-Duurzaam/backend/Controllers/login/LogoutController.php">Uitloggen</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
