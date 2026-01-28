<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Voorraad overzicht template
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voorraad - Kringloop Centrum Duurzaam</title>
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
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        .page-title {
            font-size: 1.5rem;
            font-weight: 400;
            color: #333;
            margin: 0;
        }
        .btn-action {
            background-color: #7c83db;
            color: white;
            border-radius: 6px;
            padding: 10px 20px;
            font-weight: 500;
            border: none;
            text-decoration: none;
        }
        .btn-action:hover {
            background-color: #3754DB;
            color: white;
        }
        .data-table {
            background: white;
        }
        .data-table table {
            margin: 0;
        }
        .data-table th {
            background: transparent;
            border-bottom: 2px solid #333;
            font-weight: 500;
            padding: 15px 20px;
            color: #333;
        }
        .data-table td {
            padding: 20px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        .data-table tr:last-child td {
            border-bottom: none;
        }
        .table-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            color: #666;
        }
        .pagination-nav a {
            color: #3754DB;
            text-decoration: none;
            margin: 0 5px;
        }
        .actions-btn {
            background: none;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            padding: 5px 10px;
            letter-spacing: 2px;
        }
        .alert {
            border-radius: 8px;
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

<div class="container mt-4">
    <?php if (!empty($controller->melding)): ?>
        <div class="alert alert-<?php echo $controller->meldingType; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($controller->melding); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="page-header">
        <h1 class="page-title">Voorraad</h1>
        <a href="#" class="btn-action">Nieuwe Item</a>
    </div>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox"></th>
                    <th>ID</th>
                    <th>Omschrijving</th>
                    <th>Hoeveelheid</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->voorraad)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Geen voorraad gevonden</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->voorraad as $item): ?>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo htmlspecialchars($item->artikel_id); ?></td>
                    <td><?php echo $item->hoeveelheid; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">•••</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $item->id; ?>" onclick="return confirm('Weet je zeker dat je dit item wilt verwijderen?')">Verwijderen</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span><?php echo $controller->countResultaten(); ?> Resultaten</span>
        <div class="pagination-nav">
            <a href="#">&lt; Vorige</a>
            <span>1</span>
            <a href="#">Volgende &gt;</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
