<?php
// app/models/Booking.php

class Booking extends Model {

    // ==========================================
    // TEAMMATE'S CODE (PUBLIC / USER SIDE)
    // ==========================================

    // 1. Get Details for Seat Map
    public function getShowDetails($showId) {
        $stmt = $this->db->prepare("
            SELECT s.id as show_id, s.show_time, s.base_price, 
                   m.title, m.poster, 
                   sc.name as screen_name, sc.capacity
            FROM shows s
            JOIN movies m ON s.movie_id = m.id
            JOIN screens sc ON s.screen_id = sc.id
            WHERE s.id = :id
        ");
        $stmt->execute([':id' => $showId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Get Seat Availability (Only checks CONFIRMED bookings now)
    public function getSeatMapForShow($showId) {
        $stmt = $this->db->prepare("
            SELECT s.id, s.row_label, s.seat_number, s.type,
                CASE WHEN EXISTS (
                    SELECT 1 FROM booking_seats bs
                    JOIN bookings b ON bs.booking_id = b.id
                    WHERE bs.seat_id = s.id
                    AND b.show_id = :show1
                    AND b.status = 'confirmed' -- Only confirmed bookings block seats
                ) THEN 'taken' ELSE 'available' END AS status
            FROM seats s
            WHERE s.screen_id = (SELECT screen_id FROM shows WHERE id = :show2 LIMIT 1)
            ORDER BY s.row_label, s.seat_number
        ");
        $stmt->execute([':show1' => $showId, ':show2' => $showId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get seat labels from seat IDs (for display in concessions)
    public function getSeatLabels(array $seatIds) {
        if (empty($seatIds)) return '';
        
        $placeholders = implode(',', array_fill(0, count($seatIds), '?'));
        $stmt = $this->db->prepare("
            SELECT CONCAT(row_label, seat_number) as label 
            FROM seats 
            WHERE id IN ($placeholders)
            ORDER BY row_label, seat_number
        ");
        $stmt->execute($seatIds);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return implode(', ', array_column($results, 'label'));
    }
  
    // 3. FINALIZE BOOKING (Called ONLY after Payment)
    public function finalizeBooking($data) {
        try {
            $this->db->beginTransaction();

            // A. Check Availability One Last Time (Race Condition Check)
            $check = $this->db->prepare("
                SELECT bs.seat_id FROM booking_seats bs 
                JOIN bookings b ON bs.booking_id = b.id 
                WHERE bs.seat_id = :seat AND b.show_id = :show AND b.status = 'confirmed'
            ");

            foreach ($data['seat_ids'] as $sid) {
                $check->execute([':seat' => $sid, ':show' => $data['show_id']]);
                if ($check->fetch()) {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => "Seat was just taken by another user."];
                }
            }

            // B. Get seat price per seat
            $seatPrice = $data['seat_price'] ?? ($data['ticket_price'] / count($data['seat_ids']));

            // C. Create Booking with status 'confirmed' (payment already done)
            $stmt = $this->db->prepare("
                INSERT INTO bookings (show_id, user_id, status, total_amount, payment_reference, created_at) 
                VALUES (:show, :user, 'confirmed', :total, :ref, NOW())
            ");
            $stmt->execute([
                ':show' => $data['show_id'],
                ':user' => $data['user_id'] ?? null,
                ':total' => $data['grand_total'],
                ':ref' => $data['payment_ref']
            ]);
            $bookingId = $this->db->lastInsertId();

            // D. Reserve Seats
            $insertSeat = $this->db->prepare("INSERT INTO booking_seats (booking_id, seat_id, price) VALUES (:bid, :sid, :price)");
            foreach ($data['seat_ids'] as $sid) {
                $insertSeat->execute([
                    ':bid' => $bookingId, 
                    ':sid' => $sid, 
                    ':price' => $seatPrice
                ]); 
            }

            $this->db->commit();
            return ['success' => true, 'booking_id' => $bookingId];

        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // 4. Get booking details by ID (for payment form display)
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   m.title as movie_title, 
                   m.poster as movie_poster,
                   s.show_time, 
                   scr.name as screen_name,
                   (
                       SELECT GROUP_CONCAT(CONCAT(seat.row_label, seat.seat_number) SEPARATOR ', ')
                       FROM booking_seats bs
                       JOIN seats seat ON bs.seat_id = seat.id
                       WHERE bs.booking_id = b.id
                   ) as seat_numbers
            FROM bookings b
            JOIN shows s ON b.show_id = s.id
            JOIN movies m ON s.movie_id = m.id
            JOIN screens scr ON s.screen_id = scr.id
            WHERE b.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // YOUR ADMIN CODE (REPORTS / STATS)
    // ==========================================

    // 5. Get Total Bookings Count
    public function getCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM bookings");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // 6. Get Total Revenue (Confirmed bookings only)
    public function getTotalRevenue() {
        $stmt = $this->db->query("SELECT SUM(total_amount) as total FROM bookings WHERE status = 'confirmed'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // 7. Get Recent Bookings (Limit 5 for Dashboard)
    public function getRecent() {
        // We select user_id as username for now since we don't have a Users table join yet
        $sql = "SELECT b.id, b.user_id as username, b.total_amount as amount, b.created_at as booking_date 
                FROM bookings b
                ORDER BY b.created_at DESC LIMIT 5";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 8. Get All Bookings (For Full Booking Table)
    public function getAllBookings() {
        $sql = "SELECT b.id, b.user_id as username, m.title, b.status, b.total_amount as amount, b.created_at as booking_date 
                FROM bookings b
                JOIN shows s ON b.show_id = s.id
                JOIN movies m ON s.movie_id = m.id
                ORDER BY b.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 9. Get Revenue By Movie (For Report)
    public function getRevenueByMovie() {
        $sql = "SELECT m.title, COUNT(b.id) as tickets_sold, SUM(b.total_amount) as revenue 
                FROM movies m
                JOIN shows s ON m.id = s.movie_id
                LEFT JOIN bookings b ON s.id = b.show_id AND b.status = 'confirmed'
                GROUP BY m.id, m.title
                ORDER BY revenue DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 10. Get Daily Sales (For Sales Report)
    public function getDailySales() {
        $sql = "SELECT DATE(created_at) as date, COUNT(id) as total_tickets, SUM(total_amount) as total_revenue
                FROM bookings
                WHERE status = 'confirmed'
                GROUP BY DATE(created_at)
                ORDER BY date DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}