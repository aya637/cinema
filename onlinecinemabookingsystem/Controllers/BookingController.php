<?php
// app/Controllers/BookingController.php

class BookingController extends Controller {
    protected $bookingModel;
    protected $movieModel;

    public function __construct() {
        $this->bookingModel = $this->model('Booking');
        $this->movieModel = $this->model('Movie');
    }

    public function selectSeats($showId) {
        $show = $this->bookingModel->getShowDetails($showId);
        if (!$show) die("Showtime not found.");
        $this->view('booking/seat_map', ['show' => $show]);
    }

    public function getSeatMap($showId) {
        // Clean buffer to ensure valid JSON
        if (ob_get_length()) ob_clean(); 
        header('Content-Type: application/json');

        if (!$this->bookingModel) {
            echo json_encode(['success' => false, 'message' => 'BookingModel not loaded.']);
            exit;
        }

        try {
            $seats = $this->bookingModel->getSeatMapForShow($showId);
            echo json_encode(['success' => true, 'seats' => $seats]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit; 
    }

    // Store seat selection in session (NO database entry until payment)
    public function createBooking() {
        if (ob_get_length()) ob_clean(); 
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $showId = $input['show_id'] ?? null;
        $seatIds = $input['seat_ids'] ?? [];
        $total = $input['total'] ?? 0;
        
        if (!$showId || empty($seatIds)) {
            echo json_encode(['success' => false, 'message' => 'No seats selected.']);
            exit;
        }

        try {
            // Get show details and seat labels
            $show = $this->bookingModel->getShowDetails($showId);
            if (!$show) {
                echo json_encode(['success' => false, 'message' => 'Show not found.']);
                exit;
            }

            // Get seat labels for display
            $seatLabels = $this->bookingModel->getSeatLabels($seatIds);

            $cartPayload = [
                'show_id' => $showId,
                'seat_ids' => $seatIds,
                'seat_labels' => $seatLabels,
                'ticket_price' => $total,
                'movie_title' => $show['title'],
                'show_time' => $show['show_time'],
                'base_price' => $show['base_price']
            ];

            // Require authentication before allowing booking to proceed
            if (empty($_SESSION['user_id'])) {
                // Save selection so it can be restored right after login
                $_SESSION['pending_booking_cart'] = $cartPayload;
                $_SESSION['post_login_redirect'] = BASE_URL . '/public/concessions';
                $_SESSION['auth_notice'] = 'Please sign in to confirm your booking.';

                echo json_encode([
                    'success' => false,
                    'requires_auth' => true,
                    'message' => 'Please sign in to confirm your booking.',
                    'login_url' => BASE_URL . '/public/index.php?url=login'
                ]);
                exit;
            }
            
            // Store in session (NO database entry)
            $_SESSION['booking_cart'] = $cartPayload;

            echo json_encode(['success' => true, 'message' => 'Seats stored in session']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function confirmBooking($bookingId) {
        if (ob_get_length()) ob_clean(); 
        header('Content-Type: application/json');
        
        $input = json_decode(file_get_contents('php://input'), true);
        $paymentRef = $input['payment_reference'] ?? null;
        
        if (!$paymentRef) {
            echo json_encode(['success' => false, 'message' => 'Missing payment reference']);
            exit;
        }
        
        try {
            $this->bookingModel->confirm($bookingId, $paymentRef);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    public function confirmPage($id) {
        $this->view('booking/confirm', ['booking_id' => $id]);
    }
}