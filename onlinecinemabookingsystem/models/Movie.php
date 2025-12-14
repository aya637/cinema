<?php
// app/models/Movie.php

class Movie extends Model {
    
    // ==========================================
    // TEAMMATE'S CODE (DO NOT TOUCH)
    // ==========================================

    // get paginated movies
    public function getAll($page = 1, $perPage = 12, $status = 'now_showing') {
        $offset = ($page - 1) * $perPage;

        // If asked for all rows, don't filter by status
        if ($status === 'all') {
            $sql = "SELECT id, title, description, duration_minutes, rating,genre, poster, status, created_at
                    FROM movies
                    ORDER BY created_at DESC
                    LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Tolerant status match: trims and lowercases stored value and input
        $sql = "SELECT id, title, description, duration_minutes, rating,genre, poster, status, created_at
                FROM movies
                WHERE LOWER(TRIM(COALESCE(status, ''))) LIKE LOWER(TRIM(:status))
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        // Use wildcards in case status is stored slightly differently
        $stmt->bindValue(':status', '%' . trim($status) . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM movies WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getShowtimes($movieId) {
        $stmt = $this->db->prepare("
            SELECT s.id as show_id, s.show_time, s.screen_id, scr.name AS screen_name, s.base_price
            FROM shows s
            JOIN screens scr ON s.screen_id = scr.id
            WHERE s.movie_id = :m AND s.show_time >= NOW()
            ORDER BY s.show_time ASC
        ");
        $stmt->execute([':m' => $movieId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // YOUR ADMIN CODE (UPDATED WITH GENRE & RATING)
    // ==========================================

    // 1. Get All Movies for Admin (No Pagination, All Statuses)
    public function getAllForAdmin() {
        return $this->getAll(1, 1000, 'all'); 
    }

    // 2. Get Count (Used in Dashboard Cards)
    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM movies");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // 3. Create Movie (Added: Genre, Rating, Status)
    public function create($title, $genre, $duration, $rating, $desc, $poster, $status) {
        $sql = "INSERT INTO movies (title, genre, duration_minutes, rating, description, poster, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        // Order must match the VALUES (?, ?, ?, ...) above
        return $stmt->execute([$title, $genre, $duration, $rating, $desc, $poster, $status]);
    }

    // 4. Update Movie (Added: Genre, Rating, Status)
    public function update($id, $title, $genre, $duration, $rating, $desc, $poster = null, $status = 'now_showing') {
        if ($poster) {
            // Update WITH new poster
            $sql = "UPDATE movies SET title=?, genre=?, duration_minutes=?, rating=?, description=?, poster=?, status=? WHERE id=?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$title, $genre, $duration, $rating, $desc, $poster, $status, $id]);
        } else {
            // Update WITHOUT changing poster
            $sql = "UPDATE movies SET title=?, genre=?, duration_minutes=?, rating=?, description=?, status=? WHERE id=?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$title, $genre, $duration, $rating, $desc, $status, $id]);
        }
    }

    // 5. Delete Movie
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM movies WHERE id = ?");
        return $stmt->execute([$id]);
    }
}