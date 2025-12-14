<?php
// app/models/Concession.php

class Concession extends Model {
    public function getAll() {
        // Fetch all snacks sorted by category
        $stmt = $this->db->prepare("SELECT * FROM concessions ORDER BY category, name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}