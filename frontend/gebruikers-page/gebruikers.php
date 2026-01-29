<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Gebruikersbeheer template

if (!defined('VIA_CONTROLLER')) {
    header('Location: ../../backend/Controllers/gebruiker/GebruikerController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersbeheer - Kringloop Centrum Duurzaam</title>
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
        .actions-btn { background: none; border: none; font-size: 1.4rem; cursor: pointer; padding: 5px 10px; }
        .badge-rol { padding: 5px 12px; border-radius: 12px; font-size: 0.85rem; }
        .badge-directie { background-color: #d4edda; color: #155724; }
        .badge-winkelpersoneel { background-color: #cce5ff; color: #004085; }
        .badge-magazijn { background-color: #fff3cd; color: #856404; }
        .badge-chauffeur { background-color: #f8d7da; color: #721c24; }
        .badge-geblokkeerd { background-color: #dc3545; color: white; }
        .badge-actief { background-color: #28a745; color: white; }
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
                        <li><a class="dropdown-item" href="GebruikerController.php">Gebruikersbeheer</a></li>
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

    <div class="page-header">
        <h1 class="page-title">Gebruikersbeheer</h1>
        <button class="btn btn-action" data-bs-toggle="modal" data-bs-target="#nieuweGebruikerModal">Nieuwe Gebruiker</button>
    </div>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Gebruikersnaam</th>
                    <th>Rol</th>
                    <th>Status</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->gebruikers)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Geen gebruikers gevonden</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->gebruikers as $gebruiker): ?>
                <tr>
                    <td><?php echo $gebruiker->id; ?></td>
                    <td><?php echo htmlspecialchars($gebruiker->gebruikersnaam); ?></td>
                    <td>
                        <?php
                        $rolNaam = $controller->rollen[$gebruiker->rol_id] ?? 'Onbekend';
                        $badgeClass = 'badge-rol ';
                        if ($gebruiker->rol_id == 1) $badgeClass .= 'badge-directie';
                        elseif ($gebruiker->rol_id == 2) $badgeClass .= 'badge-winkelpersoneel';
                        elseif ($gebruiker->rol_id == 3) $badgeClass .= 'badge-magazijn';
                        elseif ($gebruiker->rol_id == 4) $badgeClass .= 'badge-chauffeur';
                        ?>
                        <span class="<?php echo $badgeClass; ?>"><?php echo $rolNaam; ?></span>
                    </td>
                    <td>
                        <?php if ($gebruiker->geblokkeerd): ?>
                        <span class="badge-rol badge-geblokkeerd">Geblokkeerd</span>
                        <?php else: ?>
                        <span class="badge-rol badge-actief">Actief</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!$controller->isHuidigeGebruiker($gebruiker->id)): ?>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">...</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?edit=<?php echo $gebruiker->id; ?>">Rol wijzigen</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#resetWachtwoordModal<?php echo $gebruiker->id; ?>">Wachtwoord resetten</a></li>
                                <li><a class="dropdown-item <?php echo $gebruiker->geblokkeerd ? 'text-success' : 'text-warning'; ?>" href="?blokkeer=<?php echo $gebruiker->id; ?>"><?php echo $gebruiker->geblokkeerd ? 'Deblokkeren' : 'Blokkeren'; ?></a></li>
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $gebruiker->id; ?>" onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">Verwijderen</a></li>
                            </ul>
                        </div>
                        <?php else: ?>
                        <span class="text-muted small">Jij</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="table-footer">
        <span><?php echo count($controller->gebruikers); ?> Resultaten</span>
    </div>
</div>

<!-- Modal voor nieuwe gebruiker -->
<div class="modal fade" id="nieuweGebruikerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Gebruiker</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="toevoegen">

                    <div class="mb-3">
                        <label class="form-label">Gebruikersnaam *</label>
                        <input type="text" class="form-control" name="gebruikersnaam" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Wachtwoord *</label>
                        <input type="password" class="form-control" name="wachtwoord" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rol *</label>
                        <select class="form-select" name="rol_id" required>
                            <option value="">-- Kies een rol --</option>
                            <?php foreach ($controller->rollen as $id => $naam): ?>
                            <option value="<?php echo $id; ?>"><?php echo $naam; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-action">Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal voor rol wijzigen -->
<?php if ($controller->editGebruiker): ?>
<div class="modal fade show" id="editGebruikerModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rol Wijzigen</h5>
                <a href="GebruikerController.php" class="btn-close"></a>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="bewerken">
                    <input type="hidden" name="id" value="<?php echo $controller->editGebruiker->id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Gebruikersnaam</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($controller->editGebruiker->gebruikersnaam); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rol *</label>
                        <select class="form-select" name="rol_id" required>
                            <?php foreach ($controller->rollen as $id => $naam): ?>
                            <option value="<?php echo $id; ?>" <?php echo $id == $controller->editGebruiker->rol_id ? 'selected' : ''; ?>><?php echo $naam; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="GebruikerController.php" class="btn btn-secondary">Annuleren</a>
                    <button type="submit" class="btn btn-action">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Modals voor wachtwoord resetten (US-7) -->
<?php foreach ($controller->gebruikers as $gebruiker): ?>
<?php if (!$controller->isHuidigeGebruiker($gebruiker->id)): ?>
<div class="modal fade" id="resetWachtwoordModal<?php echo $gebruiker->id; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Wachtwoord Resetten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="reset_wachtwoord">
                    <input type="hidden" name="id" value="<?php echo $gebruiker->id; ?>">

                    <div class="mb-3">
                        <label class="form-label">Gebruikersnaam</label>
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($gebruiker->gebruikersnaam); ?>" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nieuw wachtwoord *</label>
                        <input type="password" class="form-control" name="nieuw_wachtwoord" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-action">Resetten</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
<?php endforeach; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
