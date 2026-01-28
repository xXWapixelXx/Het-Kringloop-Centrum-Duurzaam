<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Categorie beheer template
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorieën - Kringloop Centrum Duurzaam</title>
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
        <h1 class="page-title">Categorieën</h1>
        <button class="btn btn-action" data-bs-toggle="modal" data-bs-target="#nieuweCategorieModal">Voeg categorie toe</button>
    </div>

    <div class="data-table">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox"></th>
                    <th>ID</th>
                    <th>Code</th>
                    <th>Omschrijving</th>
                    <th style="width: 60px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($controller->categorieen)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Geen categorieën gevonden</td>
                </tr>
                <?php else: ?>
                <?php foreach ($controller->categorieen as $categorie): ?>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><?php echo $categorie->id; ?></td>
                    <td><?php echo htmlspecialchars($categorie->code); ?></td>
                    <td><?php echo htmlspecialchars($categorie->omschrijving); ?></td>
                    <td>
                        <div class="dropdown">
                            <button class="actions-btn" data-bs-toggle="dropdown">...</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Bewerken</a></li>
                                <li><a class="dropdown-item text-danger" href="?delete=<?php echo $categorie->id; ?>" onclick="return confirm('Weet je zeker dat je deze categorie wilt verwijderen?')">Verwijderen</a></li>
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

<!-- Modal voor nieuwe categorie -->
<div class="modal fade" id="nieuweCategorieModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Maak categorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="actie" value="toevoegen">

                    <div class="mb-3">
                        <label class="form-label">Code *</label>
                        <input type="text" class="form-control" name="code" placeholder="Bijv: Sch&So" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Omschrijving *</label>
                        <input type="text" class="form-control" name="omschrijving" placeholder="Bijv: Schoenen, Sokken" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Maak aan en voeg andere toe</button>
                    <button type="submit" class="btn btn-action">Maak aan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
