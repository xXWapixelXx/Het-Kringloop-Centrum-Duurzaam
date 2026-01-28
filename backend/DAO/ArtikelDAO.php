<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: Data Access Object (DAO) voor het beheren van artikelen in de database

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Artikel.php';

class ArtikelDAO extends Database
{
    // haalt alle artikelen op uit de database
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, categorie_id, naam, prijs_ex_btw FROM artikel");

        $artikelen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $artikelen[] = new Artikel(
                (int)$row['id'],
                (int)$row['categorie_id'],
                $row['naam'],
                (float)$row['prijs_ex_btw']
            );
        }

        return $artikelen;
    }

    // haalt één artikel op op basis van id
    public function getById(int $id): ?Artikel
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, categorie_id, naam, prijs_ex_btw FROM artikel WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Artikel(
            (int)$row['id'],
            (int)$row['categorie_id'],
            $row['naam'],
            (float)$row['prijs_ex_btw']
        );
    }

    // maakt een nieuw artikel aan in de database en geeft de nieuwe id terug
    public function create(Artikel $artikel): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO artikel (categorie_id, naam, prijs_ex_btw)
            VALUES (:categorie_id, :naam, :prijs_ex_btw)
        ");

        $stmt->bindValue(':categorie_id', $artikel->getCategorieId(), PDO::PARAM_INT);
        $stmt->bindValue(':naam', $artikel->getNaam(), PDO::PARAM_STR);
        $stmt->bindValue(':prijs_ex_btw', $artikel->getPrijsExBtw());

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // werkt een bestaand artikel bij op basis van het id
    public function update(Artikel $artikel): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE artikel
            SET categorie_id = :categorie_id,
                naam = :naam,
                prijs_ex_btw = :prijs_ex_btw
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $artikel->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':categorie_id', $artikel->getCategorieId(), PDO::PARAM_INT);
        $stmt->bindValue(':naam', $artikel->getNaam(), PDO::PARAM_STR);
        $stmt->bindValue(':prijs_ex_btw', $artikel->getPrijsExBtw());

        return $stmt->execute();
    }

    // verwijdert een artikel op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM artikel WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

