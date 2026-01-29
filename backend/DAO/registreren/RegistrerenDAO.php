<?php

declare(strict_types=1);

class RegistrerenDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Insert een nieuwe klant in de database
    public function insertUser($user): void
    {
        $sql = "INSERT INTO klant (naam, adres, plaats, telefoon, email )
                VALUES (:naam, :adres, :plaats, :telefoon, :email)";

        // Voer de insert uit
        try {
            $stmt = $this->pdo->prepare($sql);
            $insert = $stmt->execute([
                ':naam' => $user->naam,
                ':adres' => $user->adres,
                ':plaats' => $user->plaats,
                ':telefoon' => $user->telefoon,
                ':email' => $user->email
            ]);

            if (!$insert) {
                return;
            }

        } catch (Throwable $e) {
            throw new Exception('Error inserting user: ' . $e->getMessage(), 0, $e);
        }
    }
}
