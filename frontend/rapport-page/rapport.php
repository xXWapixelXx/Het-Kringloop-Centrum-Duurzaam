<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Rapportage template (US-29 en US-30)

if (!defined('VIA_CONTROLLER')) {
    header('Location: ../../backend/Controllers/rapport/RapportController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maandoverzichten - Kringloop Centrum Duurzaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #3754DB !important; padding: 12px 0; }
        .navbar-brand { font-size: 1.3rem; font-weight: 600; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-title { font-size: 1.5rem; font-weight: 400; color: #333; margin: 0; }
        .btn-action { background-color: #7c83db; color: white; border-radius: 6px; padding: 10px 20px; font-weight: 500; border: none; }
        .btn-action:hover { background-color: #3754DB; color: white; }
        .data-table { background: white; }
        .data-table th { background: transparent; border-bottom: 2px solid #333; font-weight: 500; padding: 15px 20px; color: #333; }
        .data-table td { padding: 20px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .table-footer { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; color: #666; }
        .card-rapport { border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; }
        .card-rapport .card-header { background-color: #3754DB; color: white; font-weight: 500; padding: 15px 20px; border-radius: 8px 8px 0 0; }
        .card-rapport .card-body { padding: 20px; }
        .totaal-box { background-color: #f8f9fa; padding: 15px 20px; border-radius: 8px; margin-bottom: 15px; }
        .totaal-label { font-size: 0.9rem; color: #666; }
        .totaal-waarde { font-size: 1.5rem; font-weight: 600; color: #333; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand text-white d-flex align-items-center" href="../dashboard/DashboardController.php">
            <img src="/Het-Kringloop-Centrum-Duurzaam/frontend/images/logo.png" alt="Logo" style="height: 40px; margin-right: 10px;">
            Kringloop centrum
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link text-white" href="../dashboard/DashboardController.php">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Ritten</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../planning/PlanningController.php">Ritten planning</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link text-white" href="../voorraad/VoorraadController.php">Voorraad</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Beheer</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../voorraad/VoorraadController.php">Voorraadbeheer</a></li>
                        <li><a class="dropdown-item" href="../artikel/ArtikelController.php">Artikelen</a></li>
                        <li><a class="dropdown-item" href="../klant/KlantController.php">Klanten</a></li>
                        <li><a class="dropdown-item" href="../donateur/DonateurController.php">Donateurs</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../verkopen/VerkopenController.php">Verkopen</a></li>
                    </ul>
                </li>
                <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Admin</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../gebruiker/GebruikerController.php">Gebruikersbeheer</a></li>
                        <li><a class="dropdown-item" href="../categorie/CategorieController.php">Categorieën</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="RapportController.php">Maandoverzichten</a></li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item"><span class="nav-link text-white"><?php echo htmlspecialchars($_SESSION['gebruikersnaam'] ?? ''); ?></span></li>
                <li class="nav-item"><a class="nav-link text-white" href="../login/LogoutController.php">Uitloggen</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="page-header">
        <h1 class="page-title">Maandoverzichten</h1>
    </div>

    <!-- Filter formulier -->
    <form method="GET" class="mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label">Maand:</label>
                <select class="form-select" name="maand">
                    <?php foreach ($controller->maandNamen as $num => $naam): ?>
                    <option value="<?php echo $num; ?>" <?php echo $num == $controller->maand ? 'selected' : ''; ?>><?php echo $naam; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label">Jaar:</label>
                <select class="form-select" name="jaar">
                    <?php foreach ($controller->getBeschikbareJaren() as $jaar): ?>
                    <option value="<?php echo $jaar; ?>" <?php echo $jaar == $controller->jaar ? 'selected' : ''; ?>><?php echo $jaar; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-action">Toon Overzicht</button>
            </div>
        </div>
    </form>

    <div class="row">
        <!-- US-29: Maandoverzicht binnengebrachte artikelen -->
        <div class="col-md-6">
            <div class="card-rapport">
                <div class="card-header">
                    Binnengebrachte Artikelen - <?php echo $controller->maandNamen[$controller->maand] . ' ' . $controller->jaar; ?>
                </div>
                <div class="card-body">
                    <div class="totaal-box">
                        <div class="totaal-label">Totaal aantal artikelen</div>
                        <div class="totaal-waarde"><?php echo $controller->totaalBinnengebracht; ?></div>
                    </div>
                    <div class="data-table">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Artikel</th>
                                    <th>Aantal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($controller->binnengebracht)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Geen artikelen binnengebracht in deze maand</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($controller->binnengebracht as $item): ?>
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($item['ingeboekt_op'])); ?></td>
                                    <td><?php echo htmlspecialchars($item['artikel_naam'] ?? 'Onbekend'); ?></td>
                                    <td><?php echo $item['aantal']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- US-30: Maandoverzicht opbrengst verkopen -->
        <div class="col-md-6">
            <div class="card-rapport">
                <div class="card-header">
                    Verkopen Opbrengst - <?php echo $controller->maandNamen[$controller->maand] . ' ' . $controller->jaar; ?>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="totaal-box">
                                <div class="totaal-label">Totale opbrengst (ex. BTW)</div>
                                <div class="totaal-waarde">&euro; <?php echo $controller->formatPrijs($controller->totaalOpbrengst); ?></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="totaal-box">
                                <div class="totaal-label">Aantal verkopen</div>
                                <div class="totaal-waarde"><?php echo $controller->totaalAantalVerkopen; ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="data-table">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Datum</th>
                                    <th>Artikel</th>
                                    <th>Prijs</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($controller->verkopen)): ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">Geen verkopen in deze maand</td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($controller->verkopen as $item): ?>
                                <tr>
                                    <td><?php echo date('d-m-Y', strtotime($item['verkocht_op'])); ?></td>
                                    <td><?php echo htmlspecialchars($item['artikel_naam'] ?? 'Onbekend'); ?></td>
                                    <td>&euro; <?php echo $controller->formatPrijs((float)$item['verkoop_prijs_ex_btw']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
