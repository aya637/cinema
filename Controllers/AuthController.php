<?php
// Controllers/AuthController.php
// WORKING VERSION - With Password Reset Email (Updated for your DB schema)

class AuthController extends Controller
{
    // Display login page
    public function login(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/public/index.php?url=profile');
        }

        $notice = $_SESSION['auth_notice'] ?? null;
        unset($_SESSION['auth_notice']);

        $this->view('auth/login', [
            'pageTitle' => 'Sign In',
            'notice' => $notice,
        ]);
    }

    // Handle login form submission
    public function handleLogin(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if ($password === '') {
            $errors[] = 'Please enter your password.';
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$errors) {
            if (!$user || !password_verify($password, $user['password_hash'])) {
                $errors[] = 'Invalid email or password.';
            }
        }

        if ($errors) {
            $this->view('auth/login', [
                'pageTitle' => 'Sign In',
                'errors' => $errors,
                'oldEmail' => $email,
            ]);
            return;
        }

        // Set session variables
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];

        // Handle "Remember Me"
        if ($remember) {
            try {
                $token = bin2hex(random_bytes(32));
                $hashedToken = hash('sha256', $token);
                
                if (method_exists($userModel, 'updateRememberToken')) {
                    $userModel->updateRememberToken($user['id'], $hashedToken);
                    
                    setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', false, true);
                    setcookie('remember_user', $user['id'], time() + (30 * 24 * 60 * 60), '/', '', false, true);
                }
            } catch (Exception $e) {
                // Silently fail
            }
        }

        // Restore booking cart if the user was asked to sign in mid-booking
        if (isset($_SESSION['pending_booking_cart'])) {
            $_SESSION['booking_cart'] = $_SESSION['pending_booking_cart'];
            unset($_SESSION['pending_booking_cart']);
            $_SESSION['post_login_redirect'] = $_SESSION['post_login_redirect'] ?? BASE_URL . '/public/concessions';
        }

        $redirectTo = $_SESSION['post_login_redirect'] ?? null;
        unset($_SESSION['post_login_redirect']);

        $this->redirect($redirectTo ?: BASE_URL . '/public/index.php?url=profile');
    }

    // Display signup page
    public function signup(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/public/index.php?url=profile');
        }

        $notice = $_SESSION['auth_notice'] ?? null;
        unset($_SESSION['auth_notice']);

        $this->view('auth/signup', [
            'pageTitle' => 'Sign Up',
            'notice' => $notice,
        ]);
    }

    // Handle signup form submission
    public function handleSignup(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';
        $errors = [];

        if ($name === '') {
            $errors[] = 'Please enter your full name.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($password !== $passwordConfirmation) {
            $errors[] = 'Passwords do not match.';
        }

        $userModel = new User();
        if (!$errors && $userModel->findByEmail($email)) {
            $errors[] = 'An account with this email already exists.';
        }

        if ($errors) {
            $this->view('auth/signup', [
                'pageTitle' => 'Sign Up',
                'errors' => $errors,
                'oldName' => $name,
                'oldEmail' => $email,
            ]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $userModel->create($name, $email, $passwordHash);

        if (!$userId) {
            $this->view('auth/signup', [
                'pageTitle' => 'Sign Up',
                'errors' => ['Something went wrong while creating your account. Please try again.'],
            ]);
            return;
        }

        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        // Restore booking cart if the user signed up mid-booking
        if (isset($_SESSION['pending_booking_cart'])) {
            $_SESSION['booking_cart'] = $_SESSION['pending_booking_cart'];
            unset($_SESSION['pending_booking_cart']);
            $_SESSION['post_login_redirect'] = $_SESSION['post_login_redirect'] ?? BASE_URL . '/public/concessions';
        }

        $redirectTo = $_SESSION['post_login_redirect'] ?? null;
        unset($_SESSION['post_login_redirect']);

        $this->redirect($redirectTo ?: BASE_URL . '/public/index.php?url=profile');
    }

    // Display forgot password page
    public function forgotPassword(): void
    {
        $this->view('auth/forgot-password', [
            'pageTitle' => 'Forgot Password',
        ]);
    }

    // Handle forgot password form - SENDS EMAIL
    public function handleForgotPassword(): void
    {
        $email = trim($_POST['email'] ?? '');
        $success = false;
        $message = 'If an account exists with that email, a password reset link has been sent.';

        if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user) {
                try {
                    // Generate reset token
                    $token = bin2hex(random_bytes(32));
                    $hashedToken = hash('sha256', $token);

                    // Create a new database connection for password_resets table
                    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ];
                    $db = new PDO($dsn, DB_USER, DB_PASS, $options);

                    // Delete old tokens for this email first
                    $deleteStmt = $db->prepare("DELETE FROM password_resets WHERE email = ?");
                    $deleteStmt->execute([$email]);

                    // Insert new token with current timestamp
                    $insertStmt = $db->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, NOW())");
                    $insertStmt->execute([$email, $hashedToken]);

                    // Load Mail class and send email
                    require_once ROOT_PATH . '/config/Mail.php';
                    $emailSent = Mail::sendPasswordReset($email, $user['name'], $token);

                    if ($emailSent) {
                        $success = true;
                        error_log("Password reset email sent to: " . $email);
                    } else {
                        error_log("Failed to send password reset email to: " . $email);
                        // Still show success for security
                        $success = true;
                    }
                } catch (Exception $e) {
                    error_log('Forgot Password Error: ' . $e->getMessage());
                    // Don't reveal if email exists
                    $success = true;
                }
            } else {
                // Don't reveal if email doesn't exist (security)
                $success = true;
            }
        }

        $this->view('auth/forgot-password', [
            'pageTitle' => 'Forgot Password',
            'success' => $success,
            'message' => $message
        ]);
    }

    // Display reset password page
    public function resetPassword(): void
    {
        $token = $_GET['token'] ?? '';

        if (!$token) {
            $this->view('auth/reset-password', [
                'pageTitle' => 'Reset Password',
                'errors' => ['Invalid or missing reset token.']
            ]);
            return;
        }

        $this->view('auth/reset-password', [
            'pageTitle' => 'Reset Password',
            'token' => $token
        ]);
    }

    // Handle reset password form
    public function handleResetPassword(): void
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? '';
        $errors = [];

        if (!$token) {
            $errors[] = 'Invalid reset token.';
        }

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters.';
        }

        if ($password !== $passwordConfirmation) {
            $errors[] = 'Passwords do not match.';
        }

        if (!$errors) {
            try {
                $userModel = new User();
                
                // Create database connection
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];
                $db = new PDO($dsn, DB_USER, DB_PASS, $options);
                
                // Check if token is valid and not expired (1 hour)
                $hashedToken = hash('sha256', $token);
                $stmt = $db->prepare(
                    "SELECT email FROM password_resets 
                     WHERE token = ? 
                     AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) 
                     LIMIT 1"
                );
                $stmt->execute([$hashedToken]);
                $reset = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$reset) {
                    $errors[] = 'Invalid or expired reset token.';
                } else {
                    // Update password for this user
                    $user = $userModel->findByEmail($reset['email']);
                    
                    if ($user) {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        
                        // Update password in users table
                        $updateStmt = $db->prepare(
                            "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?"
                        );
                        $updateStmt->execute([$passwordHash, $user['id']]);

                        // Delete used token
                        $deleteStmt = $db->prepare("DELETE FROM password_resets WHERE token = ?");
                        $deleteStmt->execute([$hashedToken]);

                        $_SESSION['password_reset_success'] = true;
                        $this->redirect(BASE_URL . '/public/index.php?url=login');
                        return;
                    } else {
                        $errors[] = 'User not found.';
                    }
                }
            } catch (Exception $e) {
                error_log('Password Reset Error: ' . $e->getMessage());
                $errors[] = 'An error occurred. Please try again.';
            }
        }

        $this->view('auth/reset-password', [
            'pageTitle' => 'Reset Password',
            'errors' => $errors,
            'token' => $token
        ]);
    }

    // Logout user
    public function logout(): void
    {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('remember_user', '', time() - 3600, '/');
        }

        $_SESSION = [];
        if (session_id() !== '' || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();

        header('Location: ' . BASE_URL . '/public/index.php');
        exit();
    }


    // =========================================================
    // ADMIN / STAFF LOGIN METHODS
    // =========================================================

    public function adminLogin(): void
    {
        // If already logged in, redirect to dashboard
        if (!empty($_SESSION['admin_id'])) {
            $this->redirect(BASE_URL . '/public/index.php?url=admin');
        }

        // LOAD THE VIEW
        // Since you don't have an admin folder, we point to 'admin-login' directly
        // This looks for Views/admin-login.php
        $this->view('admin-login', [
            'pageTitle' => 'Staff Access',
        ]);
    }

    public function handleAdminLogin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(BASE_URL . '/public/index.php?url=admin/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Load the Staff Model
        // (Ensure this path is correct for your setup, usually Models/Staff.php)
        require_once __DIR__ . '/../Models/Staff.php'; 
        $staffModel = new Staff(); 
        
        // Fetch user (Now includes password_hash!)
        $staff = $staffModel->getByEmail($email);

        // Verify Password
        if ($staff && password_verify($password, $staff['password_hash'])) {
            
            // SUCCESS: Set Session
            $_SESSION['admin_id'] = $staff['id'];
            $_SESSION['admin_name'] = $staff['name'];
            $_SESSION['admin_role'] = $staff['role']; 
            
            // Redirect to Dashboard
            $this->redirect(BASE_URL . '/public/index.php?url=admin');
        } else {
            // FAILURE: Redirect back with error
            $this->redirect(BASE_URL . '/public/index.php?url=admin/login&error=1');
        }
    }

    // Admin Logout
    public function adminLogout(): void
    {
        // Remove only Admin session variables
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_role']);
        unset($_SESSION['is_admin_logged_in']);

        // Redirect to Admin Login
        $this->redirect(BASE_URL . '/public/index.php?url=admin/login');
    }
}





?>