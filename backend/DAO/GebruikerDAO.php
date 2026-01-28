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
        $stmt = $db->query("
            SELECT id, gebruikersnaam, wachtwoord, rollen, is_geverifieerd
            FROM gebruiker
        ");

        $gebruikers = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $gebruikers[] = new Gebruiker(
                (int)$row['id'],
                $row['gebruikersnaam'],
                $row['wachtwoord'],
                $row['rollen'],
                (bool)$row['is_geverifieerd']
            );
        }

        return $gebruikers;
    }

    // haalt één gebruiker op op basis van id
    public function getById(int $id): ?Gebruiker
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT id, gebruikersnaam, wachtwoord, rollen, is_geverifieerd
            FROM gebruiker
            WHERE id = :id
        ");
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
            $row['rollen'],
            (bool)$row['is_geverifieerd']
        );
    }

    // haalt een gebruiker op op basis van gebruikersnaam (handig voor login)
    public function getByGebruikersnaam(string $gebruikersnaam): ?Gebruiker
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            SELECT id, gebruikersnaam, wachtwoord, rollen, is_geverifieerd
            FROM gebruiker
            WHERE gebruikersnaam = :gebruikersnaam
        ");
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
            $row['rollen'],
            (bool)$row['is_geverifieerd']
        );
    }

    // maakt een nieuwe gebruiker aan en geeft de nieuwe id terug
    public function create(Gebruiker $gebruiker): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO gebruiker (gebruikersnaam, wachtwoord, rollen, is_geverifieerd)
            VALUES (:gebruikersnaam, :wachtwoord, :rollen, :is_geverifieerd)
        ");

        $stmt->bindValue(':gebruikersnaam', $gebruiker->gebruikersnaam, PDO::PARAM_STR);
        $stmt->bindValue(':wachtwoord', $gebruiker->wachtwoord, PDO::PARAM_STR);
        $stmt->bindValue(':rollen', $gebruiker->rollen, PDO::PARAM_STR);
        $stmt->bindValue(':is_geverifieerd', $gebruiker->is_geverifieerd ? 1 : 0, PDO::PARAM_INT);

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
                rollen = :rollen,
                is_geverifieerd = :is_geverifieerd
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $gebruiker->id, PDO::PARAM_INT);
        $stmt->bindValue(':gebruikersnaam', $gebruiker->gebruikersnaam, PDO::PARAM_STR);
        $stmt->bindValue(':wachtwoord', $gebruiker->wachtwoord, PDO::PARAM_STR);
        $stmt->bindValue(':rollen', $gebruiker->rollen, PDO::PARAM_STR);
        $stmt->bindValue(':is_geverifieerd', $gebruiker->is_geverifieerd ? 1 : 0, PDO::PARAM_INT);

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

