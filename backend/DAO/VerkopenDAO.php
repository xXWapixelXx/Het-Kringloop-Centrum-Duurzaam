<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor verkopen. Extends Database; doet queries en maakt Verkopen-objecten van database-rijen.

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Verkopen.php';

class VerkopenDAO extends Database
{
    // Verbinding via parent; query; per rij Verkopen-object in array
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT * FROM verkopen");

        $verkopen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $verkopen[] = new Verkopen(
                (int)$row['id'],
                (int)($row['klant_id'] ?? 0),
                (int)($row['artikel_id'] ?? 0),
                (float)($row['verkoop_prijs_ex_btw'] ?? 0),
                $row['verkocht_op'] ?? date('Y-m-d')
            );
        }

        return $verkopen;
    }

    // prepare + bindValue(:id) + execute; één rij als Verkopen of null
    public function getById(int $id): ?Verkopen
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT * FROM verkopen WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Verkopen(
            (int)$row['id'],
            (int)($row['klant_id'] ?? 0),
            (int)($row['artikel_id'] ?? 0),
            (float)($row['verkoop_prijs_ex_btw'] ?? 0),
            $row['verkocht_op'] ?? date('Y-m-d')
        );
    }

    // prepare + bindValue(:klant_id) + execute; per rij Verkopen in array
    public function getByKlantId(int $klantId): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT * FROM verkopen WHERE klant_id = :klant_id");
        $stmt->bindValue(':klant_id', $klantId, PDO::PARAM_INT);
        $stmt->execute();

        $verkopen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $verkopen[] = new Verkopen(
                (int)$row['id'],
                (int)($row['klant_id'] ?? 0),
                (int)($row['artikel_id'] ?? 0),
                (float)($row['verkoop_prijs_ex_btw'] ?? 0),
                $row['verkocht_op'] ?? date('Y-m-d')
            );
        }

        return $verkopen;
    }

    // INSERT met prepare + bindValue voor alle velden; lastInsertId() geeft nieuwe id
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

    // UPDATE met prepare + bindValue voor alle velden; execute geeft true/false
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

    // DELETE met prepare + bindValue(:id); veilig tegen SQL-injectie
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM verkopen WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // prepare + bindValue(:jaar, :maand); JOIN met artikel en klant; US-30
    public function getMaandOverzicht(int $jaar, int $maand): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT v.id, v.artikel_id, v.verkoop_prijs_ex_btw, v.verkocht_op, a.naam as artikel_naam, k.naam as klant_naam
            FROM verkopen v
            LEFT JOIN artikel a ON v.artikel_id = a.id
            LEFT JOIN klant k ON v.klant_id = k.id
            WHERE YEAR(v.verkocht_op) = :jaar AND MONTH(v.verkocht_op) = :maand
            ORDER BY v.verkocht_op DESC
        ");
        $stmt->bindValue(':jaar', $jaar, PDO::PARAM_INT);
        $stmt->bindValue(':maand', $maand, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // prepare + bindValue(:jaar, :maand); SUM(verkoop_prijs_ex_btw) voor maand; US-30
    public function getTotaalOpbrengstMaand(int $jaar, int $maand): float
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(verkoop_prijs_ex_btw), 0) as totaal
            FROM verkopen
            WHERE YEAR(verkocht_op) = :jaar AND MONTH(verkocht_op) = :maand
        ");
        $stmt->bindValue(':jaar', $jaar, PDO::PARAM_INT);
        $stmt->bindValue(':maand', $maand, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($row['totaal'] ?? 0);
    }

    // prepare + bindValue(:jaar, :maand); COUNT(*) voor maand; US-30
    public function getTotaalAantalMaand(int $jaar, int $maand): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT COUNT(*) as totaal
            FROM verkopen
            WHERE YEAR(verkocht_op) = :jaar AND MONTH(verkocht_op) = :maand
        ");
        $stmt->bindValue(':jaar', $jaar, PDO::PARAM_INT);
        $stmt->bindValue(':maand', $maand, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['totaal'] ?? 0);
    }
}
?>
