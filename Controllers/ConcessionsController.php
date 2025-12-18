<?php
class ConcessionsController extends Controller {
   // Inside Controllers/ConcessionsController.php

public function index() {
    // ... (Authentication check remains the same) ...

    // Read from Session
    $cart = $_SESSION['booking_cart'] ?? null;

    if (!$cart) {
        // ... (Redirect if no booking session) ...
    }

    $concessionModel = $this->model('Concession');
    
    // *** CHANGE IS HERE: Use getAvailable() instead of getAll() ***
    // This ensures only items with stock > 0 are displayed to the customer.
    $items = $concessionModel->getAvailable(); 

    $this->view('concessions/catalog', [
        'items' => $items,
        'cart' => $cart // Pass session data to view
    ]);
}
}