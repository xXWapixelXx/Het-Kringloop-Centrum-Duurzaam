<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Navbar component voor alle paginas
?>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="DashboardController.php">Kringloop centrum</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="DashboardController.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Ritten
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="PlanningController.php">Ritten planning</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="VoorraadController.php">Voorraad</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Beheer
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="VoorraadController.php">Voorraadbeheer</a></li>
                        <li><a class="dropdown-item" href="ArtikelController.php">Artikelen</a></li>
                        <li><a class="dropdown-item" href="KlantController.php">Klanten</a></li>
                        <li><a class="dropdown-item" href="DonateurController.php">Donateurs</a></li>
                    </ul>
                </li>
                <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        Admin
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="GebruikerController.php">Gebruikersbeheer</a></li>
                        <li><a class="dropdown-item" href="CategorieController.php">Categorieën</a></li>
                        <li><a class="dropdown-item" href="RapportageController.php">Rapportages</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link">
                        <?php echo htmlspecialchars($_SESSION['gebruikersnaam'] ?? 'Gast'); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="LogoutController.php">Uitloggen</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
