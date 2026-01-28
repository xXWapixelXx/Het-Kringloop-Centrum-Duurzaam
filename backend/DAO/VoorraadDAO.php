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

    // verwijdert voorraad
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM voorraad WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
