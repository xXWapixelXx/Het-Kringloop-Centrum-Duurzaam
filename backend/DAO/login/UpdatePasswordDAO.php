<?php

declare(strict_types=1);

class UpdatePasswordDAO
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Update het wachtwoord van een gebruiker
    public function updatePassword(string $gebruikersnaam, string $newPassword): bool
    {
        // Hash het nieuwe wachtwoord voor veiligheid
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // SQL query om het wachtwoord bij te werken
        $sql = "UPDATE gebruiker SET wachtwoord = :wachtwoord WHERE gebruikersnaam = :gebruikersnaam";

        try {
            $stmt = $this->pdo->prepare($sql);
            // Voer de query uit met de gehashte wachtwoord en gebruikersnaam
            return $stmt->execute([
                ':wachtwoord' => $hashedPassword,
                ':gebruikersnaam' => $gebruikersnaam
            ]);
        } catch (Throwable $e) {
            throw new Exception("Error updating password: " . $e->getMessage(), 0, $e);
        }
    }
}