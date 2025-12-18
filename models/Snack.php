<?php
// models/Snack.php

require_once 'ISubject.php';
require_once 'IObserver.php';
require_once 'Database.php'; // Assuming you have a DB connection class

class Snack extends Database implements ISubject {
    private array $observers = [];
    private int $lowStockThreshold = 10; // e.g., less than 10 units is low
    
    // --- Observer Pattern Implementation (from Step 2.C) ---
    public function attach(IObserver $observer) {
        // ... (Implementation from previous response)
    }

    public function detach(IObserver $observer) {
        // ...
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }
    // --------------------------------------------------------

    // New Core Business Logic: Check and Notify
    public function updateStock($snackId, $quantitySold) {
        // 1. Update the database (subtract $quantitySold from stock for $snackId)
        // ... DB update query ...

        // 2. Get the new remaining stock
        $remainingStock = $this->getSnackStockById($snackId); 

        // 3. Check for low stock and notify observers
        if ($remainingStock <= $this->lowStockThreshold) {
            $this->notifyObservers($snackId, $remainingStock);
        }
    }
    
    // Helper function to call notify with context
    private function notifyObservers($snackId, $remainingStock) {
        // Set the state that the observers will pull
        $this->currentSnackId = $snackId;
        $this->currentStock = $remainingStock;
        $this->notify();
    }
    
    // Getters for Observers
    public function getCurrentSnackId(): int { return $this->currentSnackId; }
    public function getCurrentStock(): int { return $this->currentStock; }
    public function getSnackNameById(int $id): string {
        // ... fetch name from DB ...
        return "Snack Item $id"; 
    }
    // ... other CRUD methods ...
}