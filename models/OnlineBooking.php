<?php
// app/models/OnlineBooking.php

require_once __DIR__ . '/Model.php';

class OnlineBooking extends Model
{
    // this model uses the `moviehomepage` table inside the `onlinebooking` database
    private string $table = 'moviehomepage';

    /**
     * Get all active posters ordered by position
     */
    public function getAllPosters(bool $onlyActive = true): array
    {
        $table = $this->table;

        // Detect whether optional columns exist in the table (active, position)
        $hasActive = false;
        $hasPosition = false;
        try {
            $colStmt = $this->db->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table AND COLUMN_NAME IN ('active','position')");
            $colStmt->execute(['db' => DB_NAME, 'table' => $table]);
            $cols = $colStmt->fetchAll(PDO::FETCH_COLUMN);
            $hasActive = in_array('active', $cols, true);
            $hasPosition = in_array('position', $cols, true);
        } catch (Exception $e) {
            // If information_schema is not available for some reason, fallback to safe defaults
            $hasActive = false;
            $hasPosition = false;
        }

        // Build SQL dynamically based on available columns
        $sql = "SELECT * FROM {$table}";
        $where = [];
        if ($onlyActive && $hasActive) {
            $where[] = "active = 1";
        }
        if (!empty($where)) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        if ($hasPosition) {
            $sql .= " ORDER BY COALESCE(position, 9999) ASC, created_at DESC";
        } else {
            $sql .= " ORDER BY created_at DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get single poster
     */
    public function getPosterById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $r = $stmt->fetch();
        return $r ?: null;
    }

    /**
     * Add poster
     */
    public function addPoster(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (title, image_path, link, position, active, created_at) VALUES (:title, :image_path, :link, :position, :active, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }
}
