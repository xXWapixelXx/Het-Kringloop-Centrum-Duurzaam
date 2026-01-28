<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor het beheren van verboden artikelen

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/VerbodenArtikel.php';

class VerbodenArtikelDAO extends Database
{
    // haalt alle verboden artikelen op
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

    // haalt één verboden artikel op op basis van id
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

    // zoekt in verboden artikelen op omschrijving
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

    // maakt een nieuw verboden artikel aan en geeft de nieuwe id terug
    public function create(VerbodenArtikel $verbodenArtikel): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("INSERT INTO verboden_artikel (omschrijving) VALUES (:omschrijving)");
        $stmt->bindValue(':omschrijving', $verbodenArtikel->omschrijving, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // werkt een verboden artikel bij op basis van id
    public function update(VerbodenArtikel $verbodenArtikel): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("UPDATE verboden_artikel SET omschrijving = :omschrijving WHERE id = :id");

        $stmt->bindValue(':id', $verbodenArtikel->id, PDO::PARAM_INT);
        $stmt->bindValue(':omschrijving', $verbodenArtikel->omschrijving, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // verwijdert een verboden artikel op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM verboden_artikel WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
