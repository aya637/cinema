<?php

require_once __DIR__ . '/Model.php';

class User extends Model
{
    private string $table = 'users';

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findByRememberToken(int $userId, string $hashedToken): ?array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE id = :id AND remember_token = :token 
            LIMIT 1
        ");
        $stmt->execute([
            'id' => $userId,
            'token' => $hashedToken
        ]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function create(string $name, string $email, string $passwordHash): ?int
    {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (name, email, password_hash)
            VALUES (:name, :email, :password_hash)
        ");

        $ok = $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);

        if (!$ok) {
            return null;
        }

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
            $params[$key] = $value;
        }

        if (!$fields) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function updateRememberToken(int $userId, string $hashedToken): bool
    {
        return $this->update($userId, ['remember_token' => $hashedToken]);
    }

    public function clearRememberToken(int $userId): bool
    {
        return $this->update($userId, ['remember_token' => null]);
    }
}
