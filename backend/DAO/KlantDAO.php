<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor klanten. Extends Database; doet queries en maakt Klant-objecten van database-rijen.

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Klant.php';

class KlantDAO extends Database
{
    // Verbinding via parent; query; per rij Klant-object in array
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("
            SELECT id, naam, adres, plaats, telefoon, email
            FROM klant
        ");

        $klanten = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $klanten[] = new Klant(
                (int)$row['id'],
                $row['naam'],
                $row['adres'],
                $row['plaats'],
                $row['telefoon'],
                $row['email']
            );
        }

        return $klanten;
    }

    // prepare + bindValue(:id) + execute; één rij als Klant of null
    public function getById(int $id): ?Klant
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT id, naam, adres, plaats, telefoon, email
            FROM klant
            WHERE id = :id
        ");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Klant(
            (int)$row['id'],
            $row['naam'],
            $row['adres'],
            $row['plaats'],
            $row['telefoon'],
            $row['email']
        );
    }

    // INSERT met prepare + bindValue voor alle velden; lastInsertId() geeft nieuwe id
    public function create(Klant $klant): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO klant (naam, adres, plaats, telefoon, email)
            VALUES (:naam, :adres, :plaats, :telefoon, :email)
        ");

        $stmt->bindValue(':naam', $klant->naam, PDO::PARAM_STR);
        $stmt->bindValue(':adres', $klant->adres, PDO::PARAM_STR);
        $stmt->bindValue(':plaats', $klant->plaats, PDO::PARAM_STR);
        $stmt->bindValue(':telefoon', $klant->telefoon, PDO::PARAM_STR);
        $stmt->bindValue(':email', $klant->email, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // UPDATE met prepare + bindValue voor alle velden; execute geeft true/false
    public function update(Klant $klant): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE klant
            SET naam = :naam,
                adres = :adres,
                plaats = :plaats,
                telefoon = :telefoon,
                email = :email
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $klant->id, PDO::PARAM_INT);
        $stmt->bindValue(':naam', $klant->naam, PDO::PARAM_STR);
        $stmt->bindValue(':adres', $klant->adres, PDO::PARAM_STR);
        $stmt->bindValue(':plaats', $klant->plaats, PDO::PARAM_STR);
        $stmt->bindValue(':telefoon', $klant->telefoon, PDO::PARAM_STR);
        $stmt->bindValue(':email', $klant->email, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // DELETE met prepare + bindValue(:id); veilig tegen SQL-injectie
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM klant WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

