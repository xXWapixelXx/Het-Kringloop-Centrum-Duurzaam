<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor het beheren van verkopen in de database

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Verkopen.php';

class VerkopenDAO extends Database
{
    // haalt alle verkopen op
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, klant_id, artikel_id, verkoop_prijs_ex_btw, verkocht_op FROM verkopen");

        $verkopen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $verkopen[] = new Verkopen(
                (int)$row['id'],
                (int)$row['klant_id'],
                (int)$row['artikel_id'],
                (float)$row['verkoop_prijs_ex_btw'],
                $row['verkocht_op']
            );
        }

        return $verkopen;
    }

    // haalt één verkoop op op basis van id
    public function getById(int $id): ?Verkopen
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, klant_id, artikel_id, verkoop_prijs_ex_btw, verkocht_op FROM verkopen WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Verkopen(
            (int)$row['id'],
            (int)$row['klant_id'],
            (int)$row['artikel_id'],
            (float)$row['verkoop_prijs_ex_btw'],
            $row['verkocht_op']
        );
    }

    // haalt verkopen op per klant
    public function getByKlantId(int $klantId): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, klant_id, artikel_id, verkoop_prijs_ex_btw, verkocht_op FROM verkopen WHERE klant_id = :klant_id");
        $stmt->bindValue(':klant_id', $klantId, PDO::PARAM_INT);
        $stmt->execute();

        $verkopen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $verkopen[] = new Verkopen(
                (int)$row['id'],
                (int)$row['klant_id'],
                (int)$row['artikel_id'],
                (float)$row['verkoop_prijs_ex_btw'],
                $row['verkocht_op']
            );
        }

        return $verkopen;
    }

    // maakt een nieuwe verkoop aan en geeft de nieuwe id terug
    public function create(Verkopen $verkoop): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO verkopen (klant_id, artikel_id, verkoop_prijs_ex_btw, verkocht_op)
            VALUES (:klant_id, :artikel_id, :verkoop_prijs_ex_btw, :verkocht_op)
        ");

        $stmt->bindValue(':klant_id', $verkoop->klant_id, PDO::PARAM_INT);
        $stmt->bindValue(':artikel_id', $verkoop->artikel_id, PDO::PARAM_INT);
        $stmt->bindValue(':verkoop_prijs_ex_btw', $verkoop->verkoop_prijs_ex_btw);
        $stmt->bindValue(':verkocht_op', $verkoop->verkocht_op, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // werkt een verkoop bij op basis van id
    public function update(Verkopen $verkoop): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE verkopen
            SET klant_id = :klant_id,
                artikel_id = :artikel_id,
                verkoop_prijs_ex_btw = :verkoop_prijs_ex_btw,
                verkocht_op = :verkocht_op
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $verkoop->id, PDO::PARAM_INT);
        $stmt->bindValue(':klant_id', $verkoop->klant_id, PDO::PARAM_INT);
        $stmt->bindValue(':artikel_id', $verkoop->artikel_id, PDO::PARAM_INT);
        $stmt->bindValue(':verkoop_prijs_ex_btw', $verkoop->verkoop_prijs_ex_btw);
        $stmt->bindValue(':verkocht_op', $verkoop->verkocht_op, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // verwijdert een verkoop op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM verkopen WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
