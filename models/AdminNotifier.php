<?php
// models/AdminNotifier.php

require_once 'IObserver.php';
require_once 'ISubject.php'; 

class AdminNotifier implements IObserver {
    
   // Inside models/AdminNotifier.php

public function update(ISubject $subject) {
    if ($subject instanceof Concession) {
        
        // 1. Call the newly defined method to pull the complete list of low stock items
        $lowStockItems = $subject->getLowStockItems(); // This now works!
        
        $newNotifications = []; 

        // 2. Loop through the full list and build the notifications
        foreach ($lowStockItems as $item) {
            $snackId = $item['id'];
            $snackName = $item['name'];
            $stock = $item['stock_quantity'];
            
            // Add notification for low stock
            $newNotifications[$snackId] = [
                'type' => 'danger',
                'message' => "🚨 **LOW STOCK ALERT** for **{$snackName}**. Only **{$stock}** units remain!",
                'id' => $snackId
            ];
        }
        
        // 3. Overwrite the SESSION variable with the new, complete list
        $_SESSION['admin_snack_notifications'] = $newNotifications;
    }
}
}