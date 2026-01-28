<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Dashboard template
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
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand text-white" href="DashboardController.php">Kringloop centrum</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link text-white" href="DashboardController.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Ritten</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="PlanningController.php">Ritten planning</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="VoorraadController.php">Voorraad</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Beheer</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="VoorraadController.php">Voorraadbeheer</a></li>
                        <li><a class="dropdown-item" href="ArtikelController.php">Artikelen</a></li>
                        <li><a class="dropdown-item" href="KlantController.php">Klanten</a></li>
                    </ul>
                </li>
                <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Admin</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="GebruikerController.php">Gebruikersbeheer</a></li>
                        <li><a class="dropdown-item" href="CategorieController.php">Categorieën</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-white"><?php echo htmlspecialchars($_SESSION['gebruikersnaam'] ?? ''); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="LogoutController.php">Uitloggen</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Ritten</h5>
                    <p class="card-text">beschrijving</p>
                    <a href="PlanningController.php" class="btn-dashboard">Ga naar ritten</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Voorraad beheer</h5>
                    <p class="card-text">Beschrijving</p>
                    <a href="VoorraadController.php" class="btn-dashboard">Ga naar voorraad beheer</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Kledingstukken</h5>
                    <p class="card-text">Beschrijving</p>
                    <a href="ArtikelController.php" class="btn-dashboard">Ga naar kledingstukken</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="card-body">
                    <h5 class="card-title">Klanten</h5>
                    <p class="card-text">Beschrijving</p>
                    <a href="KlantController.php" class="btn-dashboard">Ga naar klanten</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
