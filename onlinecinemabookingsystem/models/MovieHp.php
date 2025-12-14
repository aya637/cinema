<?php
// app/models/MovieHp.php

require_once __DIR__ . '/Model.php';

class MovieHp extends Model
{
    private string $table = 'moviehomepage';

    /**
     * Get all movies with optional status filter
     */
    public function getAllMovies(string $status = 'now_showing', int $limit = null): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = :status ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Get a single movie by ID
     */
    public function getMovieById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Get movies by genre
     */
    public function getMoviesByGenre(string $genre): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE genre LIKE :genre AND status = 'now_showing' ORDER BY rating DESC");
        $stmt->execute(['genre' => "%{$genre}%"]);
        return $stmt->fetchAll();
    }

    /**
     * Search movies by title
     */
    public function searchMovies(string $query): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE title LIKE :query AND status = 'now_showing' ORDER BY rating DESC");
        $stmt->execute(['query' => "%{$query}%"]);
        return $stmt->fetchAll();
    }

    /**
     * Get featured movies (highest rated)
     */
    public function getFeaturedMovies(int $limit = 4): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = 'now_showing' ORDER BY rating DESC LIMIT :limit");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Add a new movie
     */
    public function addMovie(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (title, genre, duration, rating, emoji, description, status) 
                VALUES (:title, :genre, :duration, :rating, :emoji, :description, :status)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Update a movie
     */
    public function updateMovie(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} 
                SET title = :title, genre = :genre, duration = :duration, 
                    rating = :rating, emoji = :emoji, description = :description, status = :status
                WHERE id = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Delete a movie
     */
    public function deleteMovie(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Get movie count by status
     */
    public function getMovieCount(string $status = 'now_showing'): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM {$this->table} WHERE status = :status");
        $stmt->execute(['status' => $status]);
        $result = $stmt->fetch();
        return (int) $result['count'];
    }
}

