<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Dashboard template

// als iemand dit bestand direct opent, doorsturen naar de backend controller
if (!defined('VIA_CONTROLLER')) {
    header('Location: ../../backend/Controllers/dashboard/DashboardController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kringloop Centrum Duurzaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background-color: #3754DB !important;
            padding: 12px 0;
        }
        .navbar-brand {
            font-size: 1.3rem;
            font-weight: 600;
        }
        .dashboard-card {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 20px;
            padding: 10px;
            height: 100%;
        }
        .dashboard-card .card-body {
            padding: 25px 30px;
        }
        .dashboard-card .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .dashboard-card .card-text {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 25px;
        }
        .btn-dashboard {
            background-color: #3754DB;
            color: white;
            border-radius: 25px;
            padding: 14px 32px;
            font-weight: 500;
            border: none;
            font-size: 0.95rem;
            text-decoration: none;
            display: inline-block;
        }
        .btn-dashboard:hover {
            background-color: #2a41a8;
            color: white;
        }
        .navbar-logo {
            height: 40px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<?php
$basisPad = '/Het-Kringloop-Centrum-Duurzaam';
$urlDashboard = $basisPad . '/backend/Controllers/dashboard/DashboardController.php';
$urlLogin = $basisPad . '/backend/Controllers/login/LoginController.php';
$urlLogout = $basisPad . '/backend/Controllers/login/LogoutController.php';
$urlLogo = $basisPad . '/frontend/images/logo.png';
$rolId = isset($_SESSION['rol_id']) ? (int)$_SESSION['rol_id'] : 0;
$magRitten = ($rolId === 1 || $rolId === 2 || $rolId === 4);
$magVoorraad = ($rolId === 1 || $rolId === 3);
$magBeheerKlantDonateur = ($rolId === 1);
$magBeheerVerkopen = ($rolId === 1 || $rolId === 2);
$magAdmin = ($rolId === 1);
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand text-white d-flex align-items-center" href="<?php echo $urlDashboard; ?>">
            <img src="<?php echo $urlLogo; ?>" alt="Logo" class="navbar-logo">
            Kringloop centrum
        </a>
        <?php if (isset($controller->isIngelogd) && $controller->isIngelogd): ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $urlDashboard; ?>">Home</a>
                </li>
                <?php if ($magRitten): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Ritten</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/planning/PlanningController.php">Ritten planning</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if ($magVoorraad): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $basisPad; ?>/backend/Controllers/voorraad/VoorraadController.php">Voorraad</a>
                </li>
                <?php endif; ?>
                <?php if ($magVoorraad || $magBeheerKlantDonateur || $magBeheerVerkopen): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Beheer</a>
                    <ul class="dropdown-menu">
                        <?php if ($magVoorraad): ?>
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/voorraad/VoorraadController.php">Voorraadbeheer</a></li>
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/artikel/ArtikelController.php">Artikelen</a></li>
                        <?php endif; ?>
                        <?php if ($magBeheerKlantDonateur): ?>
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/klant/KlantController.php">Klanten</a></li>
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/donateur/DonateurController.php">Donateurs</a></li>
                        <?php endif; ?>
                        <?php if ($magBeheerVerkopen): ?>
                        <?php if ($magVoorraad || $magBeheerKlantDonateur): ?><li><hr class="dropdown-divider"></li><?php endif; ?>
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/verkopen/VerkopenController.php">Verkopen</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if ($magAdmin): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Admin</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/gebruiker/GebruikerController.php">Gebruikersbeheer</a></li>
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/categorie/CategorieController.php">Categorieën</a></li>
                        <li><a class="dropdown-item" href="<?php echo $basisPad; ?>/backend/Controllers/verbodenartikel/VerbodenArtikelController.php">Verboden artikelen</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-white">
                        <?php echo htmlspecialchars($controller->gebruikersnaam ?? ''); ?>
                        <?php if (isset($controller->rolNaam)): ?>
                            <small class="ms-2">(Rol: <?php echo htmlspecialchars($controller->rolNaam); ?>)</small>
                        <?php endif; ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?php echo $urlLogout; ?>">Uitloggen</a>
                </li>
            </ul>
        </div>
        <?php else: ?>
        <div class="navbar-nav ms-auto">
            <a class="nav-link text-white" href="<?php echo $urlLogin; ?>">Aanmelden</a>
        </div>
        <?php endif; ?>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4">
        <?php
        $isIngelogd = isset($controller->isIngelogd) && $controller->isIngelogd;
        $loginUrl = $urlLogin;
        ?>
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Ritten</h5>
                    <p class="card-text">Plan ophaal- en bezorgritten in</p>
                    <a href="<?php echo $isIngelogd ? $basisPad . '/backend/Controllers/planning/PlanningController.php' : $loginUrl; ?>" class="btn-dashboard">Ga naar ritten</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Voorraad beheer</h5>
                    <p class="card-text">Beheer de voorraad van artikelen</p>
                    <a href="<?php echo $isIngelogd ? $basisPad . '/backend/Controllers/voorraad/VoorraadController.php' : $loginUrl; ?>" class="btn-dashboard">Ga naar voorraad beheer</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Artikelen</h5>
                    <p class="card-text">Bekijk en beheer alle artikelen</p>
                    <a href="<?php echo $isIngelogd ? $basisPad . '/backend/Controllers/artikel/ArtikelController.php' : $loginUrl; ?>" class="btn-dashboard">Ga naar artikelen</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Klanten</h5>
                    <p class="card-text">Beheer klantgegevens</p>
                    <a href="<?php echo $isIngelogd ? $basisPad . '/backend/Controllers/klant/KlantController.php' : $loginUrl; ?>" class="btn-dashboard">Ga naar klanten</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Donateurs</h5>
                    <p class="card-text">Beheer donateur gegevens</p>
                    <a href="<?php echo $isIngelogd ? $basisPad . '/backend/Controllers/donateur/DonateurController.php' : $loginUrl; ?>" class="btn-dashboard">Ga naar donateurs</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Verkopen</h5>
                    <p class="card-text">Registreer verkopen en bekijk omzet</p>
                    <a href="<?php echo $isIngelogd ? $basisPad . '/backend/Controllers/verkopen/VerkopenController.php' : $loginUrl; ?>" class="btn-dashboard">Ga naar verkopen</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
