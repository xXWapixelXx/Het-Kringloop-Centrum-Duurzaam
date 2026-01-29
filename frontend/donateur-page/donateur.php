<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Donateurs beheer template

if (!defined('VIA_CONTROLLER')) {
    header('Location: ../../backend/Controllers/donateur/DonateurController.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donateurs - Kringloop Centrum Duurzaam</title>
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
        .search-box { display: flex; gap: 10px; }
        .search-box input { border-radius: 6px; border: 1px solid #ddd; padding: 8px 15px; }
        .search-box button { border-radius: 6px; }
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
                        <li><a class="dropdown-item" href="DonateurController.php">Donateurs</a></li>
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
        <h1 class="page-title">Donateurs</h1>
        <div class="d-flex gap-3 align-items-center">
            <form method="GET" class="search-box">
                <input type="text" name="zoek" placeholder="Zoek op naam..." value="<?php echo htmlspecialchars($controller->zoekterm); ?>">
                <button type="submit" class="btn btn-outline-secondary">Zoeken</button>
                <?php if (!empty($controller->zoekterm)): ?>
                <a href="DonateurController.php" class="btn btn-outline-danger">Reset</a>
                <?php endif; ?>
            </form>
            <button type="button" class="btn btn-action" data-bs-toggle="modal" data-bs-target="#nieuweDonateurModal">Nieuwe Donateur</button>
        </div>
    </div>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Adres</th>
                    <th>Telefoon</th>
                    <th>Email</th>
                    <th>Ingevoerd</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->donateurs)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Geen donateurs gevonden</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->donateurs as $item): ?>
                <tr>
                    <td><?php echo $item->id; ?></td>
                    <td><?php echo htmlspecialchars($item->getVolledigeNaam()); ?></td>
                    <td><?php echo htmlspecialchars($item->getVolledigAdres()); ?></td>
                    <td><?php echo htmlspecialchars($item->telefoon); ?></td>
                    <td><?php echo htmlspecialchars($item->email); ?></td>
                    <td><?php echo $item->datum_ingevoerd ? date('d-m-Y', strtotime($item->datum_ingevoerd)) : '-'; ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">...</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="?edit=<?php echo $item->id; ?>">Bewerken</a></li>
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $item->id; ?>" onclick="return confirm('Weet je zeker dat je deze donateur wilt verwijderen?')">Verwijderen</a></li>
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

<!-- Modal voor nieuwe donateur -->
<div class="modal fade" id="nieuweDonateurModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Donateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="toevoegen">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Voornaam *</label>
                            <input type="text" class="form-control" name="voornaam" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Achternaam *</label>
                            <input type="text" class="form-control" name="achternaam" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <input type="text" class="form-control" name="adres">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plaats</label>
                            <input type="text" class="form-control" name="plaats">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Geboortedatum</label>
                            <input type="date" class="form-control" name="geboortedatum">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefoon</label>
                            <input type="text" class="form-control" name="telefoon">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email">
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

<!-- Modal voor bewerken donateur -->
<?php if ($controller->editDonateur): ?>
<div class="modal fade show" id="editDonateurModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Donateur Bewerken</h5>
                <a href="DonateurController.php" class="btn-close"></a>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="bewerken">
                    <input type="hidden" name="id" value="<?php echo $controller->editDonateur->id; ?>">
                    <input type="hidden" name="datum_ingevoerd" value="<?php echo $controller->editDonateur->datum_ingevoerd; ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Voornaam *</label>
                            <input type="text" class="form-control" name="voornaam" value="<?php echo htmlspecialchars($controller->editDonateur->voornaam); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Achternaam *</label>
                            <input type="text" class="form-control" name="achternaam" value="<?php echo htmlspecialchars($controller->editDonateur->achternaam); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <input type="text" class="form-control" name="adres" value="<?php echo htmlspecialchars($controller->editDonateur->adres); ?>">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Plaats</label>
                            <input type="text" class="form-control" name="plaats" value="<?php echo htmlspecialchars($controller->editDonateur->plaats); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Geboortedatum</label>
                            <input type="date" class="form-control" name="geboortedatum" value="<?php echo $controller->editDonateur->geboortedatum; ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telefoon</label>
                            <input type="text" class="form-control" name="telefoon" value="<?php echo htmlspecialchars($controller->editDonateur->telefoon); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($controller->editDonateur->email); ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="DonateurController.php" class="btn btn-secondary">Annuleren</a>
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
