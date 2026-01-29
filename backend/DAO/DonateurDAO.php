<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor donateurs. Extends Database; doet queries en maakt Donateur-objecten van database-rijen.

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Donateur.php';

class DonateurDAO extends Database
{
    // Verbinding via parent; query; per rij Donateur-object in array
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, voornaam, achternaam, adres, plaats, telefoon, email, geboortedatum, datum_ingvoerd FROM donateur");

        $donateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $donateurs[] = new Donateur(
                (int)$row['id'],
                $row['voornaam'],
                $row['achternaam'],
                $row['adres'],
                $row['plaats'],
                $row['telefoon'],
                $row['email'],
                $row['geboortedatum'],
                $row['datum_ingvoerd']
            );
        }

        return $donateurs;
    }

    // prepare + bindValue(:id) + execute; één rij als Donateur of null
    public function getById(int $id): ?Donateur
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, voornaam, achternaam, adres, plaats, telefoon, email, geboortedatum, datum_ingvoerd FROM donateur WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Donateur(
            (int)$row['id'],
            $row['voornaam'],
            $row['achternaam'],
            $row['adres'],
            $row['plaats'],
            $row['telefoon'],
            $row['email'],
            $row['geboortedatum'],
            $row['datum_ingvoerd']
        );
    }

    // prepare + bindValue(:naam) met LIKE; per rij Donateur in array
    public function zoekOpNaam(string $naam): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, voornaam, achternaam, adres, plaats, telefoon, email, geboortedatum, datum_ingvoerd FROM donateur WHERE voornaam LIKE :naam OR achternaam LIKE :naam");
        $stmt->bindValue(':naam', '%' . $naam . '%', PDO::PARAM_STR);
        $stmt->execute();

        $donateurs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $donateurs[] = new Donateur(
                (int)$row['id'],
                $row['voornaam'],
                $row['achternaam'],
                $row['adres'],
                $row['plaats'],
                $row['telefoon'],
                $row['email'],
                $row['geboortedatum'],
                $row['datum_ingvoerd']
            );
        }

        return $donateurs;
    }

    // INSERT met prepare + bindValue voor alle velden; lastInsertId() geeft nieuwe id
    public function create(Donateur $donateur): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO donateur (voornaam, achternaam, adres, plaats, telefoon, email, geboortedatum, datum_ingvoerd)
            VALUES (:voornaam, :achternaam, :adres, :plaats, :telefoon, :email, :geboortedatum, :datum_ingvoerd)
        ");

        $stmt->bindValue(':voornaam', $donateur->voornaam, PDO::PARAM_STR);
        $stmt->bindValue(':achternaam', $donateur->achternaam, PDO::PARAM_STR);
        $stmt->bindValue(':adres', $donateur->adres, PDO::PARAM_STR);
        $stmt->bindValue(':plaats', $donateur->plaats, PDO::PARAM_STR);
        $stmt->bindValue(':telefoon', $donateur->telefoon, PDO::PARAM_STR);
        $stmt->bindValue(':email', $donateur->email, PDO::PARAM_STR);
        $stmt->bindValue(':geboortedatum', $donateur->geboortedatum, PDO::PARAM_STR);
        $stmt->bindValue(':datum_ingvoerd', $donateur->datum_ingevoerd, PDO::PARAM_STR);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // UPDATE met prepare + bindValue voor alle velden; execute geeft true/false
    public function update(Donateur $donateur): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE donateur
            SET voornaam = :voornaam,
                achternaam = :achternaam,
                adres = :adres,
                plaats = :plaats,
                telefoon = :telefoon,
                email = :email,
                geboortedatum = :geboortedatum,
                datum_ingvoerd = :datum_ingvoerd
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $donateur->id, PDO::PARAM_INT);
        $stmt->bindValue(':voornaam', $donateur->voornaam, PDO::PARAM_STR);
        $stmt->bindValue(':achternaam', $donateur->achternaam, PDO::PARAM_STR);
        $stmt->bindValue(':adres', $donateur->adres, PDO::PARAM_STR);
        $stmt->bindValue(':plaats', $donateur->plaats, PDO::PARAM_STR);
        $stmt->bindValue(':telefoon', $donateur->telefoon, PDO::PARAM_STR);
        $stmt->bindValue(':email', $donateur->email, PDO::PARAM_STR);
        $stmt->bindValue(':geboortedatum', $donateur->geboortedatum, PDO::PARAM_STR);
        $stmt->bindValue(':datum_ingvoerd', $donateur->datum_ingevoerd, PDO::PARAM_STR);

        return $stmt->execute();
    }

    // DELETE met prepare + bindValue(:id); veilig tegen SQL-injectie
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM donateur WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
