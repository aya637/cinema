<?php
class Showtime extends Model {

    // 1. Get All Showtimes
    public function getAll() {
        $sql = "SELECT s.id, m.title, s.show_time, sc.name as screen_name, s.base_price as price, s.movie_id,
                       (sc.capacity - (SELECT COUNT(*) FROM bookings b WHERE b.show_id = s.id AND b.status = 'confirmed')) as available_seats 
                FROM shows s
                JOIN movies m ON s.movie_id = m.id
                JOIN screens sc ON s.screen_id = sc.id
                ORDER BY s.show_time ASC";
        
        // FIX: Changed $this->conn to $this->db
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Get Count (Used in Dashboard Cards)
    public function getCount() {
        // FIX: Changed $this->conn to $this->db
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM shows");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // 3. Create Showtime
    public function create($movieId, $showTime, $screenId, $price) {
        $sql = "INSERT INTO shows (movie_id, screen_id, show_time, base_price) VALUES (?, ?, ?, ?)";
        
        // FIX: Changed $this->conn to $this->db
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$movieId, $screenId, $showTime, $price]);
    }

    // 4. Delete Showtime
    public function delete($id) {
        // FIX: Changed $this->conn to $this->db
        $stmt = $this->db->prepare("DELETE FROM shows WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // 5. Get Occupancy Stats (For Report)
    public function getOccupancyStats() {
        $sql = "SELECT m.title, sc.name as screen_name, s.show_time as start_time,
                       COUNT(b.id) as booked_seats,
                       sc.capacity as total_capacity,
                       (COUNT(b.id) * 100 / sc.capacity) as percentage
                FROM shows s
                JOIN movies m ON s.movie_id = m.id
                JOIN screens sc ON s.screen_id = sc.id
                LEFT JOIN bookings b ON s.id = b.show_id AND b.status = 'confirmed'
                GROUP BY s.id
                ORDER BY percentage DESC";
        
        // FIX: Changed $this->conn to $this->db
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}