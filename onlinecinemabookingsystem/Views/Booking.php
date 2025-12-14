<?php
// app/models/Booking.php

class Booking extends Model {

    // 1. Get Booking Details (Includes Seat Numbers)
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

    public function getShowDetails($showId) {
        $stmt = $this->db->prepare("SELECT s.id as show_id, s.show_time, s.base_price, m.title, m.poster, sc.name as screen_name, sc.capacity FROM shows s JOIN movies m ON s.movie_id = m.id JOIN screens sc ON s.screen_id = sc.id WHERE s.id = :id");
        $stmt->execute([':id' => $showId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 2. Updated Seat Map: Only 'confirmed' bookings block seats
    public function getSeatMapForShow($showId) {
        $stmt = $this->db->prepare("
            SELECT s.id, s.row_label, s.seat_number, s.type,
                CASE WHEN EXISTS (
                    SELECT 1 FROM booking_seats bs
                    JOIN bookings b ON bs.booking_id = b.id
                    WHERE bs.seat_id = s.id
                    AND b.show_id = :show1
                    AND b.status = 'confirmed' -- FIX: Only show 'confirmed' as taken
                ) THEN 'taken' ELSE 'available' END AS status
            FROM seats s
            WHERE s.screen_id = (SELECT screen_id FROM shows WHERE id = :show2 LIMIT 1)
            ORDER BY s.row_label, s.seat_number
        ");
        $stmt->execute([':show1' => $showId, ':show2' => $showId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
  
    // 3. Create Booking: Allows 'pending' overlaps (Optimistic)
    public function createAndReserve($showId, $userId, array $seatIds, $totalAmount) {
        try {
            $this->db->beginTransaction();

            // Get Seat Price
            $priceStmt = $this->db->prepare("SELECT base_price FROM shows WHERE id = :id");
            $priceStmt->execute([':id' => $showId]);
            $seatPrice = $priceStmt->fetch(PDO::FETCH_ASSOC)['base_price'] ?? 0;

            // Check if seats are ALREADY CONFIRMED (Hard reservation check)
            $check = $this->db->prepare("
                SELECT bs.seat_id FROM booking_seats bs 
                JOIN bookings b ON bs.booking_id = b.id 
                WHERE bs.seat_id = :seat AND b.show_id = :show AND b.status = 'confirmed'
                LIMIT 1
            ");

            foreach ($seatIds as $sid) {
                $check->execute([':seat' => $sid, ':show' => $showId]);
                if ($check->fetch()) {
                    $this->db->rollBack();
                    return ['success' => false, 'message' => "Seat $sid is already taken."];
                }
            }

            // Create 'pending' booking (Does not block others yet)
            $stmt = $this->db->prepare("INSERT INTO bookings (show_id, user_id, status, total_amount, created_at) VALUES (:show, :user, 'pending', :total, NOW())");
            $stmt->execute([':show' => $showId, ':user' => $userId, ':total' => $totalAmount]);
            $bookingId = $this->db->lastInsertId();

            $insertSeat = $this->db->prepare("INSERT INTO booking_seats (booking_id, seat_id, price) VALUES (:booking, :seat, :price)");
            foreach ($seatIds as $sid) {
                $insertSeat->execute([':booking' => $bookingId, ':seat' => $sid, ':price' => $seatPrice]); 
            }

            $this->db->commit();
            return ['success' => true, 'booking_id' => $bookingId];

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // 4. Confirm: THE REAL RESERVATION CHECK
    public function confirm($bookingId, $paymentReference) {
        try {
            $this->db->beginTransaction();

            // A. Get details of the booking we are trying to confirm
            $stmt = $this->db->prepare("SELECT show_id FROM bookings WHERE id = :id");
            $stmt->execute([':id' => $bookingId]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            $showId = $booking['show_id'];

            // B. Get seats in this booking
            $seatStmt = $this->db->prepare("SELECT seat_id FROM booking_seats WHERE booking_id = :id");
            $seatStmt->execute([':id' => $bookingId]);
            $mySeats = $seatStmt->fetchAll(PDO::FETCH_COLUMN);

            // C. CRITICAL CHECK: Are any of these seats already 'confirmed' by someone else?
            $checkConflict = $this->db->prepare("
                SELECT count(*) as conflict 
                FROM booking_seats bs
                JOIN bookings b ON bs.booking_id = b.id
                WHERE b.show_id = :show
                AND b.status = 'confirmed'
                AND bs.seat_id = :seat
            ");

            foreach ($mySeats as $seatId) {
                $checkConflict->execute([':show' => $showId, ':seat' => $seatId]);
                if ($checkConflict->fetch()['conflict'] > 0) {
                    $this->db->rollBack();
                    return ['success' => false, 'error' => "Sorry, one of your seats was just taken by another user."];
                }
            }

            // D. If safe, mark as confirmed
            $update = $this->db->prepare("UPDATE bookings SET status = 'confirmed', payment_reference = :pr WHERE id = :id");
            $update->execute([':pr' => $paymentReference, ':id' => $bookingId]);
            
            $this->db->commit();
            return ['success' => true];

        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}