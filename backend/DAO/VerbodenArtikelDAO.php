<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor verboden artikelen. Extends Database; doet queries en maakt VerbodenArtikel-objecten van database-rijen.

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/VerbodenArtikel.php';

class VerbodenArtikelDAO extends Database
{
    // Verbinding via parent; query zonder parameters; per rij VerbodenArtikel-object in array
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, omschrijving FROM verboden_artikel");

        $verbodenArtikelen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $verbodenArtikelen[] = new VerbodenArtikel(
                (int)$row['id'],
                $row['omschrijving']
            );
        }

        return $verbodenArtikelen;
    }

    // prepare + bindValue(:id) + execute; één rij als VerbodenArtikel of null
    public function getById(int $id): ?VerbodenArtikel
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, omschrijving FROM verboden_artikel WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new VerbodenArtikel(
            (int)$row['id'],
            $row['omschrijving']
        );
    }

    // prepare + bindValue(:zoekterm) met LIKE; per rij VerbodenArtikel in array
    public function zoekOpOmschrijving(string $zoekterm): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, omschrijving FROM verboden_artikel WHERE omschrijving LIKE :zoekterm");
        $stmt->bindValue(':zoekterm', '%' . $zoekterm . '%', PDO::PARAM_STR);
        $stmt->execute();

        $verbodenArtikelen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $verbodenArtikelen[] = new VerbodenArtikel(
                (int)$row['id'],
                $row['omschrijving']
            );
        }

        return $verbodenArtikelen;
    }

    // INSERT met prepare + bindValue(:omschrijving); lastInsertId() geeft nieuwe id
    public function create(VerbodenArtikel $verbodenArtikel): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("INSERT INTO verboden_artikel (omschrijving) VALUES (:omschrijving)");
        $stmt->bindValue(':omschrijving', $verbodenArtikel->omschrijving, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // UPDATE met prepare + bindValue voor id en omschrijving; execute geeft true/false
    public function update(VerbodenArtikel $verbodenArtikel): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("UPDATE verboden_artikel SET omschrijving = :omschrijving WHERE id = :id");

        $stmt->bindValue(':id', $verbodenArtikel->id, PDO::PARAM_INT);
        $stmt->bindValue(':omschrijving', $verbodenArtikel->omschrijving, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // DELETE met prepare + bindValue(:id); veilig tegen SQL-injectie
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM verboden_artikel WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
