<?php
// models/Staff.php

require_once __DIR__ . '/Model.php';

class Staff extends Model
{
    /**
     * Get paginated staff members
     */
    public function getAll($page = 1, $perPage = 12, $role = 'all')
    {
        $offset = ($page - 1) * $perPage;
        $params = [];

        $sql = "SELECT id, name, email, role, hire_date FROM staff";

        if ($role !== 'all') {
            $sql .= " WHERE LOWER(role) = LOWER(:role)";
            $params[':role'] = $role;
        }

        $sql .= " ORDER BY name ASC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }

        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get single staff member
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT id, name, email, role FROM staff WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get staff member by email
     */
   /**
     * Get staff member by email
     */
    public function getByEmail($email)
    {
        // OLD CODE: "SELECT id, name, email, role FROM..."
        // NEW CODE: Added ", password_hash" to the list below
        $stmt = $this->db->prepare("SELECT id, name, email, role, password_hash FROM staff WHERE email = :email LIMIT 1");
        
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Check if email already exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT id FROM staff WHERE email = :email";
        $params = [':email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() !== false;
    }

    public function add($name, $email, $plain_password, $role)
    {
        $password_hash = password_hash($plain_password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO staff (name, email, password_hash, role) 
                VALUES (:name, :email, :password_hash, :role)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password_hash' => $password_hash,
            ':role' => $role
        ]);
    }

    public function update($id, $name, $email, $role, $plain_password = null)
    {
        $sql = "UPDATE staff SET name = :name, email = :email, role = :role";
        $params = [
            ':id' => $id,
            ':name' => $name,
            ':email' => $email,
            ':role' => $role
        ];

        if ($plain_password && $plain_password !== '') {
            $sql .= ", password_hash = :password_hash";
            $params[':password_hash'] = password_hash($plain_password, PASSWORD_DEFAULT);
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM staff WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}