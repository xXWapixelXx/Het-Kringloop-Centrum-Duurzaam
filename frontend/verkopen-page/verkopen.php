<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Verkopen overzicht en omzet rapportage template

if (!defined('VIA_CONTROLLER')) {
    header('Location: ../../backend/Controllers/verkopen/VerkopenController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verkopen - Kringloop Centrum Duurzaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #3754DB !important; padding: 12px 0; }
        .navbar-brand { font-size: 1.3rem; font-weight: 600; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
        .page-title { font-size: 1.5rem; font-weight: 400; color: #333; margin: 0; }
        .btn-action { background-color: #7c83db; color: white; border-radius: 6px; padding: 10px 20px; font-weight: 500; border: none; text-decoration: none; }
        .btn-action:hover { background-color: #3754DB; color: white; }
        .data-table { background: white; }
        .data-table table { margin: 0; }
        .data-table th { background: transparent; border-bottom: 2px solid #333; font-weight: 500; padding: 15px 20px; color: #333; }
        .data-table td { padding: 20px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .data-table tr:last-child td { border-bottom: none; }
        .table-footer { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; color: #666; }
        .actions-btn { background: none; border: none; font-size: 1.4rem; cursor: pointer; padding: 5px 10px; letter-spacing: 2px; }
        .alert { border-radius: 8px; }
        .stats-card { background: linear-gradient(135deg, #3754DB 0%, #7c83db 100%); color: white; border-radius: 12px; padding: 20px; margin-bottom: 20px; }
        .stats-card h3 { font-size: 2rem; margin: 0; }
        .stats-card small { opacity: 0.8; }
        .filter-box { background: #f8f9fa; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; }
        .filter-box input { border-radius: 6px; border: 1px solid #ddd; }
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
                        <li><a class="dropdown-item" href="VerkopenController.php">Verkopen</a></li>
                    </ul>
                </li>
                <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Admin</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../gebruiker/GebruikerController.php">Gebruikersbeheer</a></li>
                        <li><a class="dropdown-item" href="../categorie/CategorieController.php">Categorieën</a></li>
                        <li><a class="dropdown-item" href="../verbodenartikel/VerbodenArtikelController.php">Verboden artikelen</a></li>
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
    <?php if (!empty($controller->melding)): ?>
        <div class="alert alert-<?php echo $controller->meldingType; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($controller->melding); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Omzet Stats -->
    <div class="row">
        <div class="col-md-4">
            <div class="stats-card">
                <small>Totaal Omzet (excl. BTW)</small>
                <h3>&euro; <?php echo number_format($controller->berekenTotaalExBtw(), 2, ',', '.'); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <small>Totaal Omzet (incl. BTW 21%)</small>
                <h3>&euro; <?php echo number_format($controller->berekenTotaalIncBtw(), 2, ',', '.'); ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <small>Aantal Verkopen</small>
                <h3><?php echo $controller->countResultaten(); ?></h3>
            </div>
        </div>
    </div>

    <!-- Datum Filter -->
    <div class="filter-box">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Van datum</label>
                <input type="date" class="form-control" name="van_datum" value="<?php echo $controller->filterVanDatum; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tot datum</label>
                <input type="date" class="form-control" name="tot_datum" value="<?php echo $controller->filterTotDatum; ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-outline-primary">Filteren</button>
                <?php if (!empty($controller->filterVanDatum) || !empty($controller->filterTotDatum)): ?>
                <a href="VerkopenController.php" class="btn btn-outline-secondary">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="page-header">
        <h1 class="page-title">Verkopen</h1>
        <button type="button" class="btn btn-action" data-bs-toggle="modal" data-bs-target="#nieuweVerkoopModal">Nieuwe Verkoop</button>
    </div>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Datum</th>
                    <th>Artikel</th>
                    <th>Klant</th>
                    <th>Prijs (ex BTW)</th>
                    <th>Prijs (incl BTW)</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->verkopen)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Geen verkopen gevonden</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->verkopen as $item): ?>
                <tr>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo date('d-m-Y', strtotime($item->verkocht_op)); ?></td>
                    <td><?php echo htmlspecialchars($controller->getArtikelNaam($item->artikel_id)); ?></td>
                    <td><?php echo htmlspecialchars($controller->getKlantNaam($item->klant_id)); ?></td>
                    <td>&euro; <?php echo number_format($item->verkoop_prijs_ex_btw, 2, ',', '.'); ?></td>
                    <td>&euro; <?php echo number_format($item->getVerkoopPrijsIncBtw(), 2, ',', '.'); ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">...</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?edit=<?php echo $item->id; ?>">Bewerken</a></li>
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $item->id; ?>" onclick="return confirm('Weet je zeker dat je deze verkoop wilt verwijderen?')">Verwijderen</a></li>
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
    </div>
</div>

<!-- Modal voor nieuwe verkoop -->
<div class="modal fade" id="nieuweVerkoopModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Verkoop Registreren</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="toevoegen">

                    <div class="mb-3">
                        <label class="form-label">Artikel *</label>
                        <select class="form-select" name="artikel_id" required>
                            <option value="">-- Kies een artikel --</option>
                            <?php foreach ($controller->artikelen as $artikel): ?>
                            <option value="<?php echo $artikel->id; ?>"><?php echo htmlspecialchars($artikel->naam); ?> - &euro;<?php echo number_format($artikel->prijs_ex_btw, 2, ',', '.'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Klant (optioneel)</label>
                        <select class="form-select" name="klant_id">
                            <option value="0">-- Anonieme verkoop --</option>
                            <?php foreach ($controller->klanten as $klant): ?>
                            <option value="<?php echo $klant->id; ?>"><?php echo htmlspecialchars($klant->naam); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Verkoopprijs (excl BTW) *</label>
                        <input type="number" class="form-control" name="verkoop_prijs_ex_btw" step="0.01" min="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Verkoopdatum *</label>
                        <input type="date" class="form-control" name="verkocht_op" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-action">Registreren</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal voor bewerken verkoop -->
<?php if ($controller->editVerkoop): ?>
<div class="modal fade show" id="editVerkoopModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verkoop Bewerken</h5>
                <a href="VerkopenController.php" class="btn-close"></a>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="bewerken">
                    <input type="hidden" name="id" value="<?php echo $controller->editVerkoop->id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Artikel *</label>
                        <select class="form-select" name="artikel_id" required>
                            <?php foreach ($controller->artikelen as $artikel): ?>
                            <option value="<?php echo $artikel->id; ?>" <?php echo $artikel->id == $controller->editVerkoop->artikel_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($artikel->naam); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Klant</label>
                        <select class="form-select" name="klant_id">
                            <option value="0">-- Anonieme verkoop --</option>
                            <?php foreach ($controller->klanten as $klant): ?>
                            <option value="<?php echo $klant->id; ?>" <?php echo $klant->id == $controller->editVerkoop->klant_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($klant->naam); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Verkoopprijs (excl BTW) *</label>
                        <input type="number" class="form-control" name="verkoop_prijs_ex_btw" step="0.01" min="0.01" value="<?php echo number_format($controller->editVerkoop->verkoop_prijs_ex_btw, 2, '.', ''); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Verkoopdatum *</label>
                        <input type="date" class="form-control" name="verkocht_op" value="<?php echo $controller->editVerkoop->verkocht_op; ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="VerkopenController.php" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-action">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
