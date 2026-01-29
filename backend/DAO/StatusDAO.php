<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor statussen. Extends Database; doet queries en maakt Status-objecten van database-rijen.

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Status.php';

class StatusDAO extends Database
{
    // Verbinding via parent; query; per rij Status-object in array
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

    // prepare + bindValue(:id) + execute; één rij als Status of null
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

    // INSERT met prepare + bindValue(:status); lastInsertId() geeft nieuwe id
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

    // UPDATE met prepare + bindValue voor id en status; execute geeft true/false
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

    // DELETE met prepare + bindValue(:id); veilig tegen SQL-injectie
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM status WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

