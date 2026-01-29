<?php
// Naam: Wail Said, Aaron Verdoold, Anwar Azarkan, Dylan Versluis
// Project: Kringloop Centrum Duurzaam
// Datum: 28-01-2026
// Beschrijving: DAO voor artikelen. Extends Database; doet queries en maakt Artikel-objecten van database-rijen (FO 3.6).

declare(strict_types=1);

require_once __DIR__ . '/../Database/Database.php';
require_once __DIR__ . '/../Models/Artikel.php';

class ArtikelDAO extends Database
{
    // Verbinding via parent; query; per rij Artikel-object in array
    public function getAll(): array
    {
        $db = $this->connect();
        $stmt = $db->query("SELECT id, categorie_id, naam, omschrijving, merk, kleur, maat, ean, prijs_ex_btw FROM artikel");

        $artikelen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $artikelen[] = new Artikel(
                (int)$row['id'],
                (int)$row['categorie_id'],
                $row['naam'],
                $row['omschrijving'] ?? '',
                $row['merk'] ?? null,
                $row['kleur'] ?? null,
                $row['maat'] ?? null,
                $row['ean'] ?? null,
                (float)$row['prijs_ex_btw']
            );
        }

        return $artikelen;
    }

    // prepare + bindValue(:id) + execute één rij als Artikel of null
    public function getById(int $id): ?Artikel
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, categorie_id, naam, omschrijving, merk, kleur, maat, ean, prijs_ex_btw FROM artikel WHERE id = :id");
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
            $row['omschrijving'],
            $row['merk'],
            $row['kleur'],
            $row['maat'],
            $row['ean'],
            (float)$row['prijs_ex_btw']
        );
    }

    // prepare + bindValue(:categorie_id) + execute; per rij Artikel in array
    public function getByCategorieId(int $categorieId): array
    {
        $db = $this->connect();
        $stmt = $db->prepare("SELECT id, categorie_id, naam, omschrijving, merk, kleur, maat, ean, prijs_ex_btw FROM artikel WHERE categorie_id = :categorie_id");
        $stmt->bindValue(':categorie_id', $categorieId, PDO::PARAM_INT);
        $stmt->execute();

        $artikelen = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $artikelen[] = new Artikel(
                (int)$row['id'],
                (int)$row['categorie_id'],
                $row['naam'],
                $row['omschrijving'] ?? '',
                $row['merk'] ?? null,
                $row['kleur'] ?? null,
                $row['maat'] ?? null,
                $row['ean'] ?? null,
                (float)$row['prijs_ex_btw']
            );
        }

        return $artikelen;
    }

    // INSERT met prepare + bindValue voor alle velden; lastInsertId() geeft nieuwe id
    public function create(Artikel $artikel): int
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            INSERT INTO artikel (categorie_id, naam, omschrijving, merk, kleur, maat, ean, prijs_ex_btw)
            VALUES (:categorie_id, :naam, :omschrijving, :merk, :kleur, :maat, :ean, :prijs_ex_btw)
        ");

        $stmt->bindValue(':categorie_id', $artikel->categorie_id, PDO::PARAM_INT);
        $stmt->bindValue(':naam', $artikel->naam, PDO::PARAM_STR);
        $stmt->bindValue(':omschrijving', $artikel->omschrijving, PDO::PARAM_STR);
        $stmt->bindValue(':merk', $artikel->merk, PDO::PARAM_STR);
        $stmt->bindValue(':kleur', $artikel->kleur, PDO::PARAM_STR);
        $stmt->bindValue(':maat', $artikel->maat, PDO::PARAM_STR);
        $stmt->bindValue(':ean', $artikel->ean, PDO::PARAM_STR);
        $stmt->bindValue(':prijs_ex_btw', $artikel->prijs_ex_btw);

        $stmt->execute();

        return (int)$db->lastInsertId();
    }

    // UPDATE met prepare + bindValue voor alle velden; execute geeft true/false
    public function update(Artikel $artikel): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("
            UPDATE artikel
            SET categorie_id = :categorie_id,
                naam = :naam,
                omschrijving = :omschrijving,
                merk = :merk,
                kleur = :kleur,
                maat = :maat,
                ean = :ean,
                prijs_ex_btw = :prijs_ex_btw
            WHERE id = :id
        ");

        $stmt->bindValue(':id', $artikel->id, PDO::PARAM_INT);
        $stmt->bindValue(':categorie_id', $artikel->categorie_id, PDO::PARAM_INT);
        $stmt->bindValue(':naam', $artikel->naam, PDO::PARAM_STR);
        $stmt->bindValue(':omschrijving', $artikel->omschrijving, PDO::PARAM_STR);
        $stmt->bindValue(':merk', $artikel->merk, PDO::PARAM_STR);
        $stmt->bindValue(':kleur', $artikel->kleur, PDO::PARAM_STR);
        $stmt->bindValue(':maat', $artikel->maat, PDO::PARAM_STR);
        $stmt->bindValue(':ean', $artikel->ean, PDO::PARAM_STR);
        $stmt->bindValue(':prijs_ex_btw', $artikel->prijs_ex_btw);

        return $stmt->execute();
    }

    // DELETE met prepare + bindValue(:id); veilig tegen SQL-injectie
    public function delete(int $id): bool
    {
        $db = $this->connect();
        $stmt = $db->prepare("DELETE FROM artikel WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>
