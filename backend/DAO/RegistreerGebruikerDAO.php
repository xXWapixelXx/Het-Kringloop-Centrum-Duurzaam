<?php

declare(strict_types=1);

class RegistreerGebruikerDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function insertUser($user): void
    {
            $sql = "INSERT INTO gebruiker (gebruikersnaam, wachtwoord)
                VALUES (:gebruikersnaam, :wachtwoord)";

        try {
            $stmt = $this->pdo->prepare($sql);
            $insert = $stmt->execute([
                ':gebruikersnaam' => $user->gebruikersnaam,
                ':wachtwoord' => password_hash($user->wachtwoord, PASSWORD_BCRYPT),
            ]);

            if (!$insert) {
                return;
            }

        } catch (Throwable $e) {
            throw new Exception('Error inserting user: ' . $e->getMessage(), 0, $e);
        }
    }
}
