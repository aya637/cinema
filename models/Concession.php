<?php
// models/Concession.php

// Include the required interfaces for the Observer Pattern
require_once 'ISubject.php';
require_once 'IObserver.php';

// Assuming 'Model' is your base class that provides the $this->db connection (PDO or similar)
class Concession extends Model implements ISubject {
    
    // --- Observer Pattern Properties ---
    private array $observers = [];
    private int $lowStockThreshold = 10; // Stock quantity at or below this number triggers an alert
    
    // *** NEW STATE PROPERTY: This holds the list of ALL low-stock items for Observers to pull ***
    private array $lowStockItems = []; 
    // The previous: private int $currentSnackId; and private int $currentStock; 
    // are now redundant for the ALL-stock notification method.

    // --- ISubject Implementation (Observer Pattern) ---

    public function attach(IObserver $observer) {
        // Prevent adding the same observer multiple times
        if (!in_array($observer, $this->observers, true)) {
            $this->observers[] = $observer;
        }
    }

    public function detach(IObserver $observer) {
        $this->observers = array_filter($this->observers, fn($o) => $o !== $observer);
    }

    public function notify() {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }


    // --- Public Facing Methods (for customer catalog) ---

    /**
     * Fetches all concession items that have stock > 0.
     */
    public function getAvailable() {
        $stmt = $this->db->prepare("SELECT id, name, base_price, category, description, image, variants FROM concessions WHERE stock_quantity > 0 ORDER BY category, name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // --- Admin Facing Methods ---

    /**
     * Fetches all concession items regardless of stock.
     */
    public function getAllForAdmin() {
        $stmt = $this->db->prepare("SELECT * FROM concessions ORDER BY category, name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Admin updates a snack item's stock quantity manually, or it's updated via a sale.
     * This method is responsible for setting the Subject's state (all low stock items) and notifying observers.
     */
    public function updateStockQuantity($snackId, $newQuantity) {
        // 1. Update stock in the database
        $stmt = $this->db->prepare("UPDATE concessions SET stock_quantity = :qty WHERE id = :id");
        $stmt->execute([':qty' => $newQuantity, ':id' => $snackId]);

        // 2. Set the state: Check ALL items for low stock status and prepare the list
        $this->setLowStockState(); 
        
        // 3. Notify all observers with the new *full* low-stock list
        $this->notify(); 
    }

    // --- Internal Helpers (for Observers/Self) ---
    
    /**
     * NEW HELPER: Fetches all items below the threshold.
     */
    private function getAllLowStockItems(): array {
        $stmt = $this->db->prepare("
            SELECT id, name, stock_quantity 
            FROM concessions 
            WHERE stock_quantity <= :threshold
            ORDER BY stock_quantity ASC
        ");
        $stmt->execute([':threshold' => $this->lowStockThreshold]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * NEW HELPER: Sets the state ($lowStockItems) that the observers will pull.
     */
    private function setLowStockState() {
        $this->lowStockItems = $this->getAllLowStockItems();
    }

    // --- Getters for Observers (IObserver::update pulls data using these) ---

    /**
     * *** THE MISSING METHOD THAT CAUSED THE FATAL ERROR ***
     * Getter for Observers to pull the complete list of low-stock items.
     */
    public function getLowStockItems(): array { 
        return $this->lowStockItems; 
    }
    
    /**
     * Getter for the low stock threshold (used by Observer to match logic)
     */
    public function getLowStockThreshold(): int {
        return $this->lowStockThreshold;
    }
    
    // Note: getSnackStockById and getSnackNameById are no longer strictly needed by 
    // the AdminNotifier since it pulls the full list, but they can remain 
    // as general-purpose methods.
    
    public function getSnackStockById(int $id): int {
        $stmt = $this->db->prepare("SELECT stock_quantity FROM concessions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return (int)($stmt->fetchColumn() ?: 0);
    }

    public function getSnackNameById(int $id): string {
        $stmt = $this->db->prepare("SELECT name FROM concessions WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return (string)($stmt->fetchColumn() ?: 'Unknown Snack');
    }
}