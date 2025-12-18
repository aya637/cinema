<?php
// models/ISubject.php (as provided - no changes needed)
interface ISubject {
    /**
     * Attach an observer to the subject.
     */
    public function attach(IObserver $observer);

    /**
     * Detach an observer from the subject.
     */
    public function detach(IObserver $observer);

    /**
     * Notify all attached observers about an event/change.
     */
    public function notify();
}

// models/IObserver.php (as provided - no changes needed)
interface IObserver {
    /**
     * Receive update from subject.
     * The observer typically pulls the state change information from the subject passed as an argument.
     * @param ISubject $subject The subject instance that triggered the update.
     */
    public function update(ISubject $subject);
}

// models/Database.php (stub - extend as needed for your DB connection)
// abstract class Database {
//     protected $pdo; // PDO instance, etc.
//     // Methods: dbQuery($sql, $params), dbUpdate($sql, $params), etc.
// }

// models/Snack.php (edited to fully comply with provided interfaces - signatures & docblocks aligned)
class Snack implements ISubject {
    private array $observers = [];
    private int $lowStockThreshold = 10;
    private ?int $currentSnackId = null;
    private ?int $currentStock = null;
    private string $currentSnackName = '';

    /**
     * Attach an observer to the subject.
     */
    public function attach(IObserver $observer): void {
        $key = array_search($observer, $this->observers, true);
        if ($key === false) {
            $this->observers[] = $observer;
        }
    }

    /**
     * Detach an observer from the subject.
     */
    public function detach(IObserver $observer): void {
        $key = array_search($observer, $this->observers, true);
        if ($key !== false) {
            unset($this->observers[$key]);
            $this->observers = array_values($this->observers);
        }
    }

    /**
     * Notify all attached observers about an event/change.
     */
    public function notify(): void {
        foreach ($this->observers as $observer) {
            $observer->update($this);
        }
    }

    // Core business logic: Update stock and check for low stock
    public function updateStock(int $snackId, int $quantitySold): void {
        // 1. Update DB stock (implement in your Database trait/class)
        // Example: $this->dbUpdate("UPDATE snacks SET stock = stock - ? WHERE id = ?", [$quantitySold, $snackId]);

        // 2. Fetch new remaining stock
        $remainingStock = $this->getSnackStockById($snackId);

        // 3. If low stock, notify ALL observers (every user)
        if ($remainingStock <= $this->lowStockThreshold) {
            $this->currentSnackId = $snackId;
            $this->currentStock = $remainingStock;
            $this->currentSnackName = $this->getSnackNameById($snackId);
            $this->notify();  // Broadcasts to every attached observer
        }
    }

    // State getters (for observers to pull data via $subject->getCurrentSnackId(), etc.)
    public function getCurrentSnackId(): ?int { return $this->currentSnackId; }
    public function getCurrentStock(): ?int { return $this->currentStock; }
    public function getCurrentSnackName(): string { return $this->currentSnackName; }

    // DB Helpers (override/extend from Database class)
    protected function getSnackStockById(int $snackId): int {
        // Real impl: return (int) $this->dbQuery("SELECT stock FROM snacks WHERE id = ?", [$snackId]),[object Object],['stock'] ?? 0;
        return rand(0, 20);  // Mock for testing
    }

    protected function getSnackNameById(int $snackId): string {
        // Real impl: return $this->dbQuery("SELECT name FROM snacks WHERE id = ?", [$snackId]),[object Object],['name'] ?? "Unknown Snack";
        return "Premium Snack #$snackId";
    }
}

// models/UserObserver.php (implements exact IObserver interface)
class UserObserver implements IObserver {
    private string $userId;
    private array $notifications = [];  // Per-user notifications (can persist to DB)

    public function __construct(string $userId) {
        $this->userId = $userId;
    }

    /**
     * Receive update from subject.
     * Pulls state from $subject and adds user-specific notification.
     */
    public function update(ISubject $subject): void {
        if ($subject instanceof Snack) {
            $snackId = $subject->getCurrentSnackId();
            $stock = $subject->getCurrentStock();
            $name = $subject->getCurrentSnackName();

            $message = "$name is critically low in stock! Only $stock units remaining.";
            $this->notifications[] = [
                'id' => $snackId,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s')
            ];

            // Production: Save to user_notifications table, send email, etc.
            // e.g., $db->insertUserNotification($this->userId, $message);
        }
    }

    public function getNotifications(): array {
        return $this->notifications;
    }

    public function clearNotifications(): void {
        $this->notifications = [];
    }
}

// Example Usage (in controller, page load, or after sales update)
// Fetch active users (from DB) and attach as observers
$snack = new Snack();  // Or load existing instance

// Simulate "every user" - replace with real user list from DB
$userIds = ['admin', 'user1', 'user32964923-1241-4ab3-9ce8-fcba99834856'];  // e.g., SELECT id FROM users WHERE active=1
$userObservers = [];
foreach ($userIds as $userId) {
    $observer = new UserObserver($userId);
    $snack->attach($observer);  // Attach EVERY user
    $userObservers[$userId] = $observer;
}

// Trigger stock update (e.g., after order processing) - notifies ALL users
$snack->updateStock(123, 15);  // Stock drops low → notifies everyone

// Current user (from $_SESSION['user_id'])
$currentUserId = 'admin';
$currentUserNotifications = $userObservers[$currentUserId]->getNotifications();

// Converted Original View Code (Observer-driven, user-specific)
if (!empty($currentUserNotifications)): ?>
    <div class="low-stock-alert">
        <h3 class="mt-0">🚨 CRITICAL STOCK ALERTS</h3>
        <ul>
        <?php foreach ($currentUserNotifications as $notification): ?>
            <li>
                <?php echo htmlspecialchars($notification['message']); ?>
                <a href="#snack-<?php echo $notification['id']; ?>">(Review Item)</a>
                <small style="color: #666;"><?php echo $notification['timestamp']; ?></small>
            </li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php
// Optional: Mark as read/clear
// $userObservers[$currentUserId]->clearNotifications();
?>