<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Artikelen overzicht template

// als iemand dit bestand direct opent, doorsturen naar de backend controller
if (!defined('VIA_CONTROLLER')) {
    header('Location: ../../backend/Controllers/artikel/ArtikelController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikelen - Kringloop Centrum Duurzaam</title>
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
        .badge-categorie {
            background-color: #e8eaff;
            color: #3754DB;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.85rem;
        }
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
                <li class="nav-item">
                    <a class="nav-link text-white" href="../dashboard/DashboardController.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Ritten</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../planning/PlanningController.php">Ritten planning</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../voorraad/VoorraadController.php">Voorraad</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" data-bs-toggle="dropdown">Beheer</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../voorraad/VoorraadController.php">Voorraadbeheer</a></li>
                        <li><a class="dropdown-item" href="ArtikelController.php">Artikelen</a></li>
                        <li><a class="dropdown-item" href="../klant/KlantController.php">Klanten</a></li>
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
                <li class="nav-item">
                    <span class="nav-link text-white"><?php echo htmlspecialchars($_SESSION['gebruikersnaam'] ?? ''); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white" href="../login/LogoutController.php">Uitloggen</a>
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
        <h1 class="page-title">Artikelen</h1>
        <button type="button" class="btn btn-action" data-bs-toggle="modal" data-bs-target="#nieuwArtikelModal">Nieuw Artikel</button>
    </div>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox"></th>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Categorie</th>
                    <th>Merk</th>
                    <th>Prijs (ex BTW)</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->artikelen)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Geen artikelen gevonden</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->artikelen as $item): ?>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo htmlspecialchars($item->naam); ?></td>
                    <td><span class="badge-categorie"><?php echo htmlspecialchars($controller->getCategorieNaam($item->categorie_id)); ?></span></td>
                    <td><?php echo htmlspecialchars($item->merk ?? '-'); ?></td>
                    <td>&euro; <?php echo number_format($item->prijs_ex_btw, 2, ',', '.'); ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">...</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?edit=<?php echo $item->id; ?>">Bewerken</a></li>
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $item->id; ?>" onclick="return confirm('Weet je zeker dat je dit artikel wilt verwijderen?')">Verwijderen</a></li>
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

<!-- Modal voor nieuw artikel -->
<div class="modal fade" id="nieuwArtikelModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuw Artikel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="toevoegen">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Naam *</label>
                            <input type="text" class="form-control" name="naam" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categorie *</label>
                            <select class="form-select" name="categorie_id" required>
                                <option value="">-- Kies een categorie --</option>
                                <?php foreach ($controller->categorieen as $cat): ?>
                                <option value="<?php echo $cat->id; ?>"><?php echo htmlspecialchars($cat->categorie); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Omschrijving</label>
                        <textarea class="form-control" name="omschrijving" rows="2"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" class="form-control" name="merk">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kleur</label>
                            <input type="text" class="form-control" name="kleur">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Maat</label>
                            <input type="text" class="form-control" name="maat">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">EAN Code</label>
                            <input type="text" class="form-control" name="ean">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prijs (ex BTW) *</label>
                            <input type="number" class="form-control" name="prijs_ex_btw" step="0.01" min="0" value="0.00" required>
                        </div>
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

<!-- Modal voor bewerken artikel -->
<?php if ($controller->editArtikel): ?>
<div class="modal fade show" id="editArtikelModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Artikel Bewerken</h5>
                <a href="ArtikelController.php" class="btn-close" aria-label="Sluiten"></a>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="bewerken">
                    <input type="hidden" name="id" value="<?php echo $controller->editArtikel->id; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Naam *</label>
                            <input type="text" class="form-control" name="naam" value="<?php echo htmlspecialchars($controller->editArtikel->naam); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Categorie *</label>
                            <select class="form-select" name="categorie_id" required>
                                <option value="">-- Kies een categorie --</option>
                                <?php foreach ($controller->categorieen as $cat): ?>
                                <option value="<?php echo $cat->id; ?>" <?php echo $cat->id == $controller->editArtikel->categorie_id ? 'selected' : ''; ?>><?php echo htmlspecialchars($cat->categorie); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Omschrijving</label>
                        <textarea class="form-control" name="omschrijving" rows="2"><?php echo htmlspecialchars($controller->editArtikel->omschrijving ?? ''); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" class="form-control" name="merk" value="<?php echo htmlspecialchars($controller->editArtikel->merk ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Kleur</label>
                            <input type="text" class="form-control" name="kleur" value="<?php echo htmlspecialchars($controller->editArtikel->kleur ?? ''); ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Maat</label>
                            <input type="text" class="form-control" name="maat" value="<?php echo htmlspecialchars($controller->editArtikel->maat ?? ''); ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">EAN Code</label>
                            <input type="text" class="form-control" name="ean" value="<?php echo htmlspecialchars($controller->editArtikel->ean ?? ''); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Prijs (ex BTW) *</label>
                            <input type="number" class="form-control" name="prijs_ex_btw" step="0.01" min="0" value="<?php echo number_format($controller->editArtikel->prijs_ex_btw, 2, '.', ''); ?>" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="ArtikelController.php" class="btn btn-secondary">Annuleren</a>
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
