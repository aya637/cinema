<?php
class PaymentController extends Controller {
    
    public function form() {
        // Enforce login before payment
        if (empty($_SESSION['user_id'])) {
            $_SESSION['auth_notice'] = 'Please sign in to complete your booking.';
            $_SESSION['post_login_redirect'] = BASE_URL . '/public/concessions';
            header("Location: " . BASE_URL . "/public/index.php?url=login");
            exit;
        }

        $cart = $_SESSION['booking_cart'] ?? null;
        if (!$cart) { header("Location: index.php?url=movies"); exit; }

        $snackTotal = floatval($_GET['snack_total'] ?? 0);
        $grandTotal = floatval($_GET['grand_total'] ?? 0);

        $this->view('payment/form', [
            'cart' => $cart,
            'snack_total' => $snackTotal,
            'grand_total' => $grandTotal
        ]);
    }

    public function process() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_SESSION['user_id'])) {
                $_SESSION['auth_notice'] = 'Please sign in to complete your booking.';
                $_SESSION['post_login_redirect'] = BASE_URL . '/public/concessions';
                header("Location: " . BASE_URL . "/public/index.php?url=login");
                exit;
            }

            $cart = $_SESSION['booking_cart'] ?? null;
            if (!$cart) {
                die("Session expired. Please start over.");
            }

            $grandTotal = floatval($_POST['grand_total'] ?? 0);
            $paymentRef = 'TXN-' . strtoupper(uniqid());

            $bookingModel = $this->model('Booking');

            // --- CREATE BOOKING IN DATABASE ONLY AFTER PAYMENT ---
            $result = $bookingModel->finalizeBooking([
                'show_id' => $cart['show_id'],
                'user_id' => $_SESSION['user_id'] ?? null,
                'seat_ids' => $cart['seat_ids'],
                'ticket_price' => $cart['ticket_price'],
                'seat_price' => $cart['base_price'], // Price per seat
                'grand_total' => $grandTotal,
                'payment_ref' => $paymentRef
            ]);

            if ($result['success']) {
                // Clear Session
                unset($_SESSION['booking_cart']);
                
                // Show Success Message
                $this->view('payment/success', [
                    'booking_id' => $result['booking_id'],
                    'payment_ref' => $paymentRef,
                    'amount' => $grandTotal
                ]);
            } else {
                die("Payment Failed: " . $result['message']);
            }
        }
    }
}