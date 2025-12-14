<?php
class ConcessionsController extends Controller {
    public function index() {
        // Require authentication for snack selection
        if (empty($_SESSION['user_id'])) {
            $_SESSION['auth_notice'] = 'Please sign in to continue with your booking.';
            $_SESSION['post_login_redirect'] = BASE_URL . '/public/concessions';
            header("Location: " . BASE_URL . "/public/index.php?url=login");
            exit;
        }

        // Read from Session
        $cart = $_SESSION['booking_cart'] ?? null;

        if (!$cart) {
            // If no session, redirect to movies (prevents direct access error)
            header("Location: index.php?url=movies");
            exit;
        }

        $concessionModel = $this->model('Concession');
        $items = $concessionModel->getAll();

        $this->view('concessions/catalog', [
            'items' => $items,
            'cart' => $cart // Pass session data to view
        ]);
    }
}