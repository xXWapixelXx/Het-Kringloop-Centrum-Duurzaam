<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor het beheren van planningen in de database

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Planning.php';

class PlanningDAO extends Database
{
    // haalt alle planningen op
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("
            SELECT id, artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op
            FROM planning
        ");

        $planningen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $planningen[] = new Planning(
                (int)$row['id'],
                (int)$row['artikel_id'],
                (int)$row['klant_id'],
                $row['kenteken'],
                $row['ophalen_of_bezorgen'],
                $row['afspraak_op']
            );
        }

        return $planningen;
    }

    // haalt één planning op op basis van id
    public function getById(int $id): ?Planning
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT id, artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op
            FROM planning
            WHERE id = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Planning(
            (int)$row['id'],
            (int)$row['artikel_id'],
            (int)$row['klant_id'],
            $row['kenteken'],
            $row['ophalen_of_bezorgen'],
            $row['afspraak_op']
        );
    }

    // maakt een nieuwe planning aan en geeft de nieuwe id terug
    public function create(Planning $planning): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO planning (artikel_id, klant_id, kenteken, ophalen_of_bezorgen, afspraak_op)
            VALUES (:artikel_id, :klant_id, :kenteken, :ophalen_of_bezorgen, :afspraak_op)
        ");

        $stmt->bindValue(':artikel_id', $planning->artikel_id, PDO::PARAM_INT);
        $stmt->bindValue(':klant_id', $planning->klant_id, PDO::PARAM_INT);
        $stmt->bindValue(':kenteken', $planning->kenteken, PDO::PARAM_STR);
        $stmt->bindValue(':ophalen_of_bezorgen', $planning->ophalen_of_bezorgen, PDO::PARAM_STR);
        $stmt->bindValue(':afspraak_op', $planning->afspraak_op, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // werkt een planning bij op basis van id
    public function update(Planning $planning): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE planning
            SET artikel_id = :artikel_id,
                klant_id = :klant_id,
                kenteken = :kenteken,
                ophalen_of_bezorgen = :ophalen_of_bezorgen,
                afspraak_op = :afspraak_op
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $planning->id, PDO::PARAM_INT);
        $stmt->bindValue(':artikel_id', $planning->artikel_id, PDO::PARAM_INT);
        $stmt->bindValue(':klant_id', $planning->klant_id, PDO::PARAM_INT);
        $stmt->bindValue(':kenteken', $planning->kenteken, PDO::PARAM_STR);
        $stmt->bindValue(':ophalen_of_bezorgen', $planning->ophalen_of_bezorgen, PDO::PARAM_STR);
        $stmt->bindValue(':afspraak_op', $planning->afspraak_op, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // verwijdert een planning op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM planning WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

