<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor het beheren van voorraad in de database

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Voorraad.php';

class VoorraadDAO extends Database
{
    // haalt alle voorraadregels op
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("
            SELECT id, artikel_id, locatie, aantal, status_id, ingeboekt_op
            FROM voorraad
        ");

        $voorraadLijst = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $voorraadLijst[] = new Voorraad(
                (int)$row['id'],
                (int)$row['artikel_id'],
                $row['locatie'],
                (int)$row['aantal'],
                (int)$row['status_id'],
                $row['ingeboekt_op']
            );
        }

        return $voorraadLijst;
    }

    // haalt één voorraadregel op op basis van id
    public function getById(int $id): ?Voorraad
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT id, artikel_id, locatie, aantal, status_id, ingeboekt_op
            FROM voorraad
            WHERE id = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Voorraad(
            (int)$row['id'],
            (int)$row['artikel_id'],
            $row['locatie'],
            (int)$row['aantal'],
            (int)$row['status_id'],
            $row['ingeboekt_op']
        );
    }

    // haalt alle voorraadregels op voor één artikel
    public function getByArtikelId(int $artikelId): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT id, artikel_id, locatie, aantal, status_id, ingeboekt_op
            FROM voorraad
            WHERE artikel_id = :artikel_id
        ");
        $stmt->bindValue(':artikel_id', $artikelId, PDO::PARAM_INT);
        $stmt->execute();

        $voorraadLijst = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $voorraadLijst[] = new Voorraad(
                (int)$row['id'],
                (int)$row['artikel_id'],
                $row['locatie'],
                (int)$row['aantal'],
                (int)$row['status_id'],
                $row['ingeboekt_op']
            );
        }

        return $voorraadLijst;
    }

    // maakt een nieuwe voorraadregel aan en geeft de nieuwe id terug
    public function create(Voorraad $voorraad): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO voorraad (artikel_id, locatie, aantal, status_id, ingeboekt_op)
            VALUES (:artikel_id, :locatie, :aantal, :status_id, :ingeboekt_op)
        ");

        $stmt->bindValue(':artikel_id', $voorraad->artikel_id, PDO::PARAM_INT);
        $stmt->bindValue(':locatie', $voorraad->locatie, PDO::PARAM_STR);
        $stmt->bindValue(':aantal', $voorraad->aantal, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $voorraad->status_id, PDO::PARAM_INT);
        $stmt->bindValue(':ingeboekt_op', $voorraad->ingeboekt_op, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // werkt een voorraadregel bij op basis van id
    public function update(Voorraad $voorraad): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE voorraad
            SET artikel_id = :artikel_id,
                locatie = :locatie,
                aantal = :aantal,
                status_id = :status_id,
                ingeboekt_op = :ingeboekt_op
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $voorraad->id, PDO::PARAM_INT);
        $stmt->bindValue(':artikel_id', $voorraad->artikel_id, PDO::PARAM_INT);
        $stmt->bindValue(':locatie', $voorraad->locatie, PDO::PARAM_STR);
        $stmt->bindValue(':aantal', $voorraad->aantal, PDO::PARAM_INT);
        $stmt->bindValue(':status_id', $voorraad->status_id, PDO::PARAM_INT);
        $stmt->bindValue(':ingeboekt_op', $voorraad->ingeboekt_op, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // verwijdert een voorraadregel op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM voorraad WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

