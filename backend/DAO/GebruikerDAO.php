<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor gebruikers. Extends Database om connect() te gebruiken; doet queries en maakt Gebruiker-objecten van database-rijen.

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Gebruiker.php';

class GebruikerDAO extends Database
{
    // Verbinding via parent, query zonder parameters; per rij een Gebruiker-object maken en in array teruggeven
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, gebruikersnaam, wachtwoord, rol_id, IFNULL(geblokkeerd, 0) as geblokkeerd FROM gebruiker");

        $gebruikers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $gebruikers[] = new Gebruiker(
                (int)$row['id'],
                $row['gebruikersnaam'],
                $row['wachtwoord'],
                (int)$row['rol_id'],
                (int)$row['geblokkeerd']
            );
        }

        return $gebruikers;
    }

    // prepare + bindValue(:id) + execute: veilig tegen SQL-injectie; één rij ophalen en als Gebruiker-object teruggeven
    public function getById(int $id): ?Gebruiker
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, gebruikersnaam, wachtwoord, rol_id, IFNULL(geblokkeerd, 0) as geblokkeerd FROM gebruiker WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Gebruiker(
            (int)$row['id'],
            $row['gebruikersnaam'],
            $row['wachtwoord'],
            (int)$row['rol_id'],
            (int)$row['geblokkeerd']
        );
    }

    // Voor login: prepare + bindValue(:gebruikersnaam) + execute; voorkomt SQL-injectie; geeft Gebruiker-object of null
    public function getByGebruikersnaam(string $gebruikersnaam): ?Gebruiker
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, gebruikersnaam, wachtwoord, rol_id, IFNULL(geblokkeerd, 0) as geblokkeerd FROM gebruiker WHERE gebruikersnaam = :gebruikersnaam");
        $stmt->bindValue(':gebruikersnaam', $gebruikersnaam, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Gebruiker(
            (int)$row['id'],
            $row['gebruikersnaam'],
            $row['wachtwoord'],
            (int)$row['rol_id'],
            (int)$row['geblokkeerd']
        );
    }

    // Zelfde patroon: prepare, bindValue(:rol_id), execute; per rij een Gebruiker in array
    public function getByRolId(int $rolId): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, gebruikersnaam, wachtwoord, rol_id, IFNULL(geblokkeerd, 0) as geblokkeerd FROM gebruiker WHERE rol_id = :rol_id");
        $stmt->bindValue(':rol_id', $rolId, PDO::PARAM_INT);
        $stmt->execute();

        $gebruikers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $gebruikers[] = new Gebruiker(
                (int)$row['id'],
                $row['gebruikersnaam'],
                $row['wachtwoord'],
                (int)$row['rol_id'],
                (int)$row['geblokkeerd']
            );
        }

        return $gebruikers;
    }

    // INSERT met prepared statement (alle velden gebonden); lastInsertId() geeft de nieuwe id terug
    public function create(Gebruiker $gebruiker): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO gebruiker (gebruikersnaam, wachtwoord, rol_id, geblokkeerd)
            VALUES (:gebruikersnaam, :wachtwoord, :rol_id, :geblokkeerd)
        ");

        $stmt->bindValue(':gebruikersnaam', $gebruiker->gebruikersnaam, PDO::PARAM_STR);
        $stmt->bindValue(':wachtwoord', $gebruiker->wachtwoord, PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $gebruiker->rol_id, PDO::PARAM_INT);
        $stmt->bindValue(':geblokkeerd', $gebruiker->geblokkeerd ?? 0, PDO::PARAM_INT);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // UPDATE met prepare + bindValue voor alle velden; execute geeft true/false
    public function update(Gebruiker $gebruiker): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE gebruiker
            SET gebruikersnaam = :gebruikersnaam,
                wachtwoord = :wachtwoord,
                rol_id = :rol_id,
                geblokkeerd = :geblokkeerd
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $gebruiker->id, PDO::PARAM_INT);
        $stmt->bindValue(':gebruikersnaam', $gebruiker->gebruikersnaam, PDO::PARAM_STR);
        $stmt->bindValue(':wachtwoord', $gebruiker->wachtwoord, PDO::PARAM_STR);
        $stmt->bindValue(':rol_id', $gebruiker->rol_id, PDO::PARAM_INT);
        $stmt->bindValue(':geblokkeerd', $gebruiker->geblokkeerd ?? 0, PDO::PARAM_INT);

        return $stmt->execute();
    }

    // Zet geblokkeerd om (0->1 of 1->0) met één UPDATE; US-32
    public function toggleBlokkeer(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("UPDATE gebruiker SET geblokkeerd = NOT IFNULL(geblokkeerd, 0) WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // DELETE met prepare + bindValue(:id); veilig tegen SQL-injectie
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM gebruiker WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
