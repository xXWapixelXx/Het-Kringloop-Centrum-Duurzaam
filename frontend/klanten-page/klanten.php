<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Klanten beheer template
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klanten - Kringloop Centrum Duurzaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-4">
    <?php if (!empty($controller->melding)): ?>
        <div class="alert alert-<?php echo $controller->meldingType; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($controller->melding); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="page-header">
        <h1 class="page-title">Klanten</h1>
        <button class="btn btn-action" data-bs-toggle="modal" data-bs-target="#nieuweKlantModal">Nieuwe Klant</button>
    </div>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox"></th>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>Email</th>
                    <th>Telefoon</th>
                    <th>Woonplaats</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->klanten)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Geen klanten gevonden</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->klanten as $klant): ?>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><?php echo $klant->id; ?></td>
                    <td><?php echo htmlspecialchars($klant->voornaam . ' ' . $klant->achternaam); ?></td>
                    <td><?php echo htmlspecialchars($klant->email); ?></td>
                    <td><?php echo htmlspecialchars($klant->telefoonnummer); ?></td>
                    <td><?php echo htmlspecialchars($klant->woonplaats); ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">...</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Bewerken</a></li>
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $klant->id; ?>" onclick="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')">Verwijderen</a></li>
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

<!-- Modal voor nieuwe klant -->
<div class="modal fade" id="nieuweKlantModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nieuwe Klant Toevoegen</h5>
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
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Telefoonnummer</label>
                        <input type="text" class="form-control" name="telefoonnummer">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Adres</label>
                        <input type="text" class="form-control" name="adres">
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Postcode</label>
                            <input type="text" class="form-control" name="postcode">
                        </div>
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Woonplaats</label>
                            <input type="text" class="form-control" name="woonplaats">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
