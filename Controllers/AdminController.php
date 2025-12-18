<?php
class AdminController extends Controller {


   public function __construct() {
        // 1. Start Session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. SECURITY CHECK: strict check for 'admin_id'
        // Normal users have 'user_id', Admins have 'admin_id'.
        
        // If the user is NOT an admin...
        if (empty($_SESSION['admin_id'])) {
            
            // Check: Are they a normal customer?
            if (!empty($_SESSION['user_id'])) {
                // Yes, they are a logged-in customer -> Kick them to Homepage
                header("Location: " . BASE_URL . "/public/");
            } else {
                // No, they are just a guest -> Kick them to Admin Login
                header("Location: " . BASE_URL . "/public/index.php?url=admin/login");
            }
            
            exit(); // Stop script execution immediately
        }
    }

    // 1. SHOW DASHBOARD
    public function dashboard() {
        $movieModel = new Movie();
        $bookingModel = new Booking();
        $showtimeModel = new Showtime();

        // Fetch Stats
        $totalMovies = $movieModel->getCount();
        $totalBookings = $bookingModel->getCount();
        $totalRevenue = $bookingModel->getTotalRevenue();
        $totalShowtimes = $showtimeModel->getCount(); 

        // Fetch Data
        $movies = $movieModel->getAllForAdmin();
        $revenueByMovie = $bookingModel->getRevenueByMovie();
        $recentBookings = $bookingModel->getRecent();
        $allBookings = $bookingModel->getAllBookings();
        $showtimes = $showtimeModel->getAll();

        // New Report Data
        $dailySales = $bookingModel->getDailySales();
        $occupancyData = $showtimeModel->getOccupancyStats();

        // Load View (Using ROOT_PATH from index.php)
        include ROOT_PATH . '/Views/admin-dashboard.php';
    }

    // 2. ADD MOVIE
    public function addMovie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posterPath = 'https://via.placeholder.com/300x400';
            
            if (!empty($_FILES['poster']['name'])) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $fileName = time() . "_" . basename($_FILES['poster']['name']);
                if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetDir . $fileName)) {
                    $posterPath = "uploads/" . $fileName; // Store relative path
                }
            }

            $movieModel = new Movie();
            // This calls the function we just fixed in Models/Movie.php
           $status = $_POST['status'] ?? 'now_showing';

            $movieModel->create(
            $_POST['title'], 
            $_POST['genre'], 
            $_POST['duration'], 
            $_POST['rating'], // <--- New Field
            $_POST['description'], 
            $posterPath,
            $status
    );

            // Correct Redirect for your Router
            header("Location: /onlinecinemabookingsystem/public/admin");
            exit();
        }
    }

    // 3. UPDATE MOVIE
    public function updateMovie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $posterPath = null;
            if (!empty($_FILES['poster']['name'])) {
                $targetDir = ROOT_PATH . "/public/uploads/";
                $fileName = time() . "_" . basename($_FILES['poster']['name']);
                if (move_uploaded_file($_FILES['poster']['tmp_name'], $targetDir . $fileName)) {
                    $posterPath = "uploads/" . $fileName;
                }
            }

            $movieModel = new Movie();
            $status = $_POST['status'] ?? 'now_showing';

            $movieModel->update(
            $_POST['id'], 
            $_POST['title'], 
            $_POST['genre'], 
            $_POST['duration'], 
            $_POST['rating'], // <--- New Field
            $_POST['description'], 
            $posterPath,
            $status
    );

            header("Location: /onlinecinemabookingsystem/public/admin");
            exit();
        }
    }

    // 4. DELETE MOVIE
    public function deleteMovie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $movieModel = new Movie();
            $movieModel->delete($_POST['id']);
            header("Location: /onlinecinemabookingsystem/public/admin");
            exit();
        }
    }

    // 5. ADD SHOWTIME
    public function addShowtime() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dateTime = $_POST['date'] . ' ' . $_POST['time'] . ':00';
            $model = new Showtime();
            $model->create($_POST['movie_id'], $dateTime, $_POST['screen_name'], $_POST['price']);
            header("Location: /onlinecinemabookingsystem/public/admin");
            exit();
        }
    }

    // 6. DELETE SHOWTIME
    public function deleteShowtime() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new Showtime();
            $model->delete($_POST['id']);
            header("Location: /onlinecinemabookingsystem/public/admin");
            exit();
        }
    }
    
    // 7. REPORTS (For links)
    public function salesReport() {
        $bookingModel = new Booking();
        $dailySales = $bookingModel->getDailySales();
        $revenueByMovie = $bookingModel->getRevenueByMovie();
        include ROOT_PATH . '/Views/Sales.php'; 
    }

    public function occupancyReport() {
        $showtimeModel = new Showtime();
        $occupancyData = $showtimeModel->getOccupancyStats();
        include ROOT_PATH . '/Views/Occupancy.php'; 
    }
    // Inside Controllers/AdminController.php
// Inside Controllers/AdminController.php

// 8. SNACKS MANAGEMENT PAGE
public function manageSnacks() {
    $concessionModel = new Concession();

    // 1. Fetch all snacks for the admin table
    $snacks = $concessionModel->getAllForAdmin();

    // 2. Fetch Notifications from Observer (AdminNotifier stores them here)
    $snackNotifications = $_SESSION['admin_snack_notifications'] ?? [];

    // Optional: Clear notifications after they are viewed (or leave them until manually dismissed)
    // unset($_SESSION['admin_snack_notifications']); 
    
    // Load the Admin View
    include ROOT_PATH . '/Views/admin-snack-management.php'; 
}
// Inside Controllers/AdminController.php

// Inside Controllers/AdminController.php

public function updateSnackStock() {
    // 1. Process the POST request and update the database
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['snack_id'], $_POST['new_stock'])) {
        
        $concessionModel = new Concession(); 
        
        // --- FIX START: Attach the observer before the update! ---
        // This ensures AdminNotifier is ready to catch the low stock event.
        $adminNotifier = new AdminNotifier(); 
        $concessionModel->attach($adminNotifier); 
        // --- FIX END ---
        
        $snackId = (int)$_POST['snack_id'];
        $newStock = (int)$_POST['new_stock'];
        
        // Call the model method, which triggers the check and notify()
        $concessionModel->updateStockQuantity($snackId, $newStock); 
        
        // --- FIX: REMOVE THE UNCONDITIONAL CLEARING OF NOTIFICATIONS! ---
        // This line: unset($_SESSION['admin_snack_notifications']); is removed.
        // Notifications are now cleared/set within AdminNotifier based on the current state.
        
        // You might want to set a temporary success message (optional)
        $_SESSION['success_message'] = "Stock for Snack ID #{$snackId} updated successfully.";

        // 3. Redirect to the manage snacks page to prevent form resubmission
        header('Location: index.php?url=admin/manageSnacks'); // Changed URL for clarity
        exit;
    }
    // ... handle failure or direct access ...
}
}