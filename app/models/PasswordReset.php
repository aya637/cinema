<?php
// app/models/PasswordReset.php

require_once __DIR__ . '/Model.php';

class PasswordReset extends Model
{
    private string $table = 'password_resets';

    /**
     * Create a password reset token
     */
    public function createToken(string $email): string
    {
        // Generate a secure random token
        $token = bin2hex(random_bytes(32));
        
        // Delete any existing tokens for this email
        $this->deleteByEmail($email);
        
        // Insert new token
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (email, token, created_at)
            VALUES (:email, :token, NOW())
        ");
        
        $stmt->execute([
            'email' => $email,
            'token' => hash('sha256', $token)
        ]);
        
        return $token; // Return the plain token (not hashed) for the email link
    }

    /**
     * Find a valid token
     */
    public function findValidToken(string $token): ?array
    {
        $hashedToken = hash('sha256', $token);
        
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE token = :token
            AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            LIMIT 1
        ");
        
        $stmt->execute(['token' => $hashedToken]);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }

    /**
     * Delete token after use
     */
    public function deleteToken(string $token): bool
    {
        $hashedToken = hash('sha256', $token);
        
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE token = :token");
        return $stmt->execute(['token' => $hashedToken]);
    }

    /**
     * Delete all tokens for an email
     */
    public function deleteByEmail(string $email): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE email = :email");
        return $stmt->execute(['email' => $email]);
    }

    /**
     * Clean up expired tokens (older than 1 hour)
     */
    public function cleanupExpired(): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM {$this->table}
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        return $stmt->execute();
    }
}