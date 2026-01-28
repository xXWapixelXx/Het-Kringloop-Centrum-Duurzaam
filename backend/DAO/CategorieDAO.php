<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor het beheren van categorieën in de database

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Categorie.php';

class CategorieDAO extends Database
{
    // haalt alle categorieën op
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, categorie FROM categorie");

        $categorieen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $categorieen[] = new Categorie(
                (int)$row['id'],
                $row['categorie']
            );
        }

        return $categorieen;
    }

    // haalt één categorie op op basis van id
    public function getById(int $id): ?Categorie
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, categorie FROM categorie WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }

        return new Categorie(
            (int)$row['id'],
            $row['categorie']
        );
    }

    // maakt een nieuwe categorie aan en geeft de nieuwe id terug
    public function create(Categorie $categorie): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO categorie (categorie)
            VALUES (:categorie)
        ");

        $stmt->bindValue(':categorie', $categorie->getCategorie(), PDO::PARAM_STR);
        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // werkt een categorie bij op basis van id
    public function update(Categorie $categorie): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE categorie
            SET categorie = :categorie
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $categorie->getId(), PDO::PARAM_INT);
        $stmt->bindValue(':categorie', $categorie->getCategorie(), PDO::PARAM_STR);

        return $stmt->execute();
    }

    // verwijdert een categorie op basis van id
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM categorie WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}

