<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor het beheren van gebruikers in de database

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Gebruiker.php';

class GebruikerDAO extends Database
{
    // haalt alle gebruikers op
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

    // haalt één gebruiker op op basis van id
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

    // haalt een gebruiker op op basis van gebruikersnaam (voor login)
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

    // haalt gebruikers op per rol
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

    // maakt een nieuwe gebruiker aan en geeft de nieuwe id terug
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

    // werkt een gebruiker bij op basis van id
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

    // blokkeer of deblokkeer een gebruiker (US-32)
    public function toggleBlokkeer(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("UPDATE gebruiker SET geblokkeerd = NOT IFNULL(geblokkeerd, 0) WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // verwijdert een gebruiker op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM gebruiker WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
