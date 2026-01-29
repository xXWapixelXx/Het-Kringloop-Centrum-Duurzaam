<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Ritten planning overzicht template

if (!defined('VIA_CONTROLLER')) {
    header('Location: ../../backend/Controllers/planning/PlanningController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ritten Planning - Kringloop Centrum Duurzaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #fff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background-color: #3754DB !important; padding: 12px 0; }
        .navbar-brand { font-size: 1.3rem; font-weight: 600; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
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
        .badge-ophalen { background-color: #d4edda; color: #155724; padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; }
        .badge-bezorgen { background-color: #cce5ff; color: #004085; padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; }
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
                        <li><a class="dropdown-item" href="PlanningController.php">Ritten planning</a></li>
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
                    </ul>
                </li>
                <?php if (isset($_SESSION['rol_id']) && $_SESSION['rol_id'] == 1): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Admin</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../gebruiker/GebruikerController.php">Gebruikersbeheer</a></li>
                        <li><a class="dropdown-item" href="../categorie/CategorieController.php">Categorieën</a></li>
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

    <div class="page-header">
        <h1 class="page-title">Ritten Planning</h1>
        <?php if (!$controller->alleenBekijken): ?>
        <button type="button" class="btn btn-action" data-bs-toggle="modal" data-bs-target="#nieuweRitModal">Nieuwe Rit</button>
        <?php endif; ?>
    </div>

    <!-- Datum filter  -->
    <form method="GET" class="mb-3">
        <div class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label">Filter op datum:</label>
                <input type="date" class="form-control" name="filter_datum" value="<?php echo htmlspecialchars($controller->filterDatum); ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-action">Filteren</button>
            </div>
            <?php if ($controller->filterDatum !== ''): ?>
            <div class="col-auto">
                <a href="PlanningController.php" class="btn btn-secondary">Wis filter</a>
            </div>
            <?php endif; ?>
        </div>
    </form>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Klant</th>
                    <th>Adres (ophalen/bezorgen)</th>
                    <th>Artikel</th>
                    <th>Kenteken</th>
                    <th>Type</th>
                    <th>Afspraak</th>
                    <?php if (!$controller->alleenBekijken): ?><th style="width: 60px;"></th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->planningen)): ?>
                <tr>
                    <td colspan="<?php echo $controller->alleenBekijken ? 7 : 8; ?>" class="text-center text-muted py-4">Geen ritten gepland</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->planningen as $item): ?>
                <tr>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo htmlspecialchars($controller->getKlantNaam($item->klant_id)); ?></td>
                    <td><?php echo htmlspecialchars($controller->getKlantAdres($item->klant_id)); ?></td>
                    <td><?php echo htmlspecialchars($controller->getArtikelNaam($item->artikel_id)); ?></td>
                    <td><?php echo htmlspecialchars($item->kenteken); ?></td>
                    <td>
                        <span class="badge-<?php echo $item->ophalen_of_bezorgen; ?>">
                            <?php echo ucfirst($item->ophalen_of_bezorgen); ?>
                        </span>
                    </td>
                    <td><?php echo date('d-m-Y H:i', strtotime($item->afspraak_op)); ?></td>
                    <td>
                        <?php if (!$controller->alleenBekijken): ?>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">...</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?edit=<?php echo $item->id; ?>">Bewerken</a></li>
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $item->id; ?>" onclick="return confirm('Weet je zeker dat je deze rit wilt verwijderen?')">Verwijderen</a></li>
                            </ul>
                        </div>
                        <?php endif; ?>
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

<!-- Modal voor nieuwe rit -->
<div class="modal fade" id="nieuweRitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Rit Plannen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="toevoegen">

                    <div class="mb-3">
                        <label class="form-label">Klant *</label>
                        <select class="form-select" name="klant_id" required>
                            <option value="">-- Kies een klant --</option>
                            <?php foreach ($controller->klanten as $klant): ?>
                            <option value="<?php echo $klant->id; ?>"><?php echo htmlspecialchars($klant->naam); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Artikel (optioneel)</label>
                        <select class="form-select" name="artikel_id">
                            <option value="0">-- Geen artikel --</option>
                            <?php foreach ($controller->artikelen as $artikel): ?>
                            <option value="<?php echo $artikel->id; ?>"><?php echo htmlspecialchars($artikel->naam); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kenteken *</label>
                        <input type="text" class="form-control" name="kenteken" placeholder="XX-XXX-X" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select class="form-select" name="ophalen_of_bezorgen" required>
                            <option value="ophalen">Ophalen</option>
                            <option value="bezorgen">Bezorgen</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Afspraak datum/tijd *</label>
                        <input type="datetime-local" class="form-control" name="afspraak_op" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-action">Inplannen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal voor bewerken rit -->
<?php if ($controller->editPlanning): ?>
<div class="modal fade show" id="editRitModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rit Bewerken</h5>
                <a href="PlanningController.php" class="btn-close"></a>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="bewerken">
                    <input type="hidden" name="id" value="<?php echo $controller->editPlanning->id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Klant *</label>
                        <select class="form-select" name="klant_id" required>
                            <?php foreach ($controller->klanten as $klant): ?>
                            <option value="<?php echo $klant->id; ?>" <?php echo $klant->id == $controller->editPlanning->klant_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($klant->naam); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Artikel</label>
                        <select class="form-select" name="artikel_id">
                            <option value="0">-- Geen artikel --</option>
                            <?php foreach ($controller->artikelen as $artikel): ?>
                            <option value="<?php echo $artikel->id; ?>" <?php echo $artikel->id == $controller->editPlanning->artikel_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($artikel->naam); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kenteken *</label>
                        <input type="text" class="form-control" name="kenteken" value="<?php echo htmlspecialchars($controller->editPlanning->kenteken); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type *</label>
                        <select class="form-select" name="ophalen_of_bezorgen" required>
                            <option value="ophalen" <?php echo $controller->editPlanning->ophalen_of_bezorgen == 'ophalen' ? 'selected' : ''; ?>>Ophalen</option>
                            <option value="bezorgen" <?php echo $controller->editPlanning->ophalen_of_bezorgen == 'bezorgen' ? 'selected' : ''; ?>>Bezorgen</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Afspraak datum/tijd *</label>
                        <input type="datetime-local" class="form-control" name="afspraak_op" value="<?php echo date('Y-m-d\TH:i', strtotime($controller->editPlanning->afspraak_op)); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="PlanningController.php" class="btn btn-secondary">Annuleren</a>
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
