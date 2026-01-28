<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor het beheren van statussen in de database

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Status.php';

class StatusDAO extends Database
{
    // haalt alle statussen op
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, status FROM status");

        $statussen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $statussen[] = new Status(
                (int)$row['id'],
                $row['status']
            );
        }

        return $statussen;
    }

    // haalt één status op op basis van id
    public function getById(int $id): ?Status
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, status FROM status WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Status(
            (int)$row['id'],
            $row['status']
        );
    }

    // maakt een nieuwe status aan en geeft de nieuwe id terug
    public function create(Status $status): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO status (status)
            VALUES (:status)
        ");

        $stmt->bindValue(':status', $status->status, PDO::PARAM_STR);
        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // werkt een status bij op basis van id
    public function update(Status $status): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE status
            SET status = :status
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $status->id, PDO::PARAM_INT);
        $stmt->bindValue(':status', $status->status, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // verwijdert een status op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM status WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

