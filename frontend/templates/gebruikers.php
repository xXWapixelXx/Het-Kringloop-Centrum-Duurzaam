<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Gebruikersbeheer template
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersbeheer - Kringloop Centrum Duurzaam</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<?php include __DIR__ . '/../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2>Gebruikersbeheer</h2>
    <p class="text-muted">Beheer medewerker accounts en rollen.</p>

    <?php if (!empty($controller->melding)): ?>
        <div class="alert alert-<?php echo $controller->meldingType; ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($controller->melding); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Alle Gebruikers</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Gebruikersnaam</th>
                                <th>Rol</th>
                                <th>Acties</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($controller->gebruikers as $gebruiker): ?>
                            <tr>
                                <td><?php echo $gebruiker->id; ?></td>
                                <td><?php echo htmlspecialchars($gebruiker->gebruikersnaam); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $controller->getRolKleur($gebruiker->rol_id); ?>">
                                        <?php echo $controller->rollen[$gebruiker->rol_id] ?? 'Onbekend'; ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!$controller->isHuidigeGebruiker($gebruiker->id)): ?>
                                    <a href="?delete=<?php echo $gebruiker->id; ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">
                                        Verwijderen
                                    </a>
                                    <?php else: ?>
                                    <span class="text-muted">Huidige gebruiker</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Nieuwe Medewerker</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <input type="hidden" name="actie" value="toevoegen">

                        <div class="mb-3">
                            <label for="gebruikersnaam" class="form-label">Gebruikersnaam</label>
                            <input type="text" class="form-control" id="gebruikersnaam" name="gebruikersnaam" required>
                        </div>

                        <div class="mb-3">
                            <label for="wachtwoord" class="form-label">Wachtwoord</label>
                            <input type="password" class="form-control" id="wachtwoord" name="wachtwoord" required>
                        </div>

                        <div class="mb-3">
                            <label for="rol_id" class="form-label">Rol</label>
                            <select class="form-select" id="rol_id" name="rol_id" required>
                                <option value="">Selecteer een rol...</option>
                                <?php foreach ($controller->rollen as $id => $naam): ?>
                                <option value="<?php echo $id; ?>"><?php echo $naam; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Toevoegen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
