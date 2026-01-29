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
    // haalt alle voorraad op
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT * FROM voorraad");

        $voorraadLijst = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $voorraad = new Voorraad();
            $voorraad->id = (int)$row['id'];
            $voorraad->artikel_id = $row['artikel_id'] ?? 0;
            $voorraad->hoeveelheid = $row['hoeveelheid'] ?? $row['aantal'] ?? 0;
            $voorraadLijst[] = $voorraad;
        }

        return $voorraadLijst;
    }

    // haalt één voorraad op
    public function getById(int $id): ?Voorraad
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT * FROM voorraad WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        $voorraad = new Voorraad();
        $voorraad->id = (int)$row['id'];
        $voorraad->artikel_id = $row['artikel_id'] ?? 0;
        $voorraad->hoeveelheid = $row['hoeveelheid'] ?? $row['aantal'] ?? 0;

        return $voorraad;
    }

    // voeg nieuwe voorraad toe (functioneel ontwerp 3.4: locatie, aantal, wel/niet reparatie, verkoop-gereed)
    public function create(Voorraad $voorraad): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO voorraad (artikel_id, locatie, aantal, status_id, wel_reparatie, verkoopgereed, ingeboekt_op)
            VALUES (:artikel_id, 'Magazijn', :aantal, 1, 0, 0, NOW())
        ");
        $stmt->bindValue(':artikel_id', (int)$voorraad->artikel_id, PDO::PARAM_INT);
        $stmt->bindValue(':aantal', (int)$voorraad->hoeveelheid, PDO::PARAM_INT);
        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // verwijdert voorraad
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM voorraad WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // haalt maandoverzicht binnengebrachte artikelen op (US-29)
    public function getMaandOverzicht(int $jaar, int $maand): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT v.id, v.artikel_id, v.aantal, v.ingeboekt_op, a.naam as artikel_naam
            FROM voorraad v
            LEFT JOIN artikel a ON v.artikel_id = a.id
            WHERE YEAR(v.ingeboekt_op) = :jaar AND MONTH(v.ingeboekt_op) = :maand
            ORDER BY v.ingeboekt_op DESC
        ");
        $stmt->bindValue(':jaar', $jaar, PDO::PARAM_INT);
        $stmt->bindValue(':maand', $maand, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // tel totaal aantal binnengebrachte artikelen per maand (US-29)
    public function getTotaalMaand(int $jaar, int $maand): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(aantal), 0) as totaal
            FROM voorraad
            WHERE YEAR(ingeboekt_op) = :jaar AND MONTH(ingeboekt_op) = :maand
        ");
        $stmt->bindValue(':jaar', $jaar, PDO::PARAM_INT);
        $stmt->bindValue(':maand', $maand, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['totaal'] ?? 0);
    }
}
?>
