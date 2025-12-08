<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PasswordReset.php';
require_once __DIR__ . '/../helpers/EmailHelper.php';

class AuthController extends Controller
{
    public function login(): void
    {
        // If already logged in, redirect to profile
        if (!empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/profile');
        }

        $this->view('auth/login', [
            'pageTitle' => 'Sign In',
        ]);
    }

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
            $token = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $token);
            
            // Save token to database
            $userModel->updateRememberToken($user['id'], $hashedToken);
            
            // Set cookie for 30 days
            setcookie(
                'remember_token',
                $token,
                time() + (30 * 24 * 60 * 60), // 30 days
                '/',
                '',
                false,
                true // HTTP only
            );
            
            setcookie(
                'remember_user',
                $user['id'],
                time() + (30 * 24 * 60 * 60),
                '/',
                '',
                false,
                true
            );
        }

        $this->redirect(BASE_URL . '/profile');
    }

    public function signup(): void
    {
        // If already logged in, redirect to profile
        if (!empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/profile');
        }

        $this->view('auth/signup', [
            'pageTitle' => 'Sign Up',
        ]);
    }

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

        $this->redirect(BASE_URL . '/profile');
    }

    public function forgotPassword(): void
    {
        $this->view('auth/forgot-password', [
            'pageTitle' => 'Forgot Password',
        ]);
    }

    public function handleForgotPassword(): void
    {
        $email = trim($_POST['email'] ?? '');
        $errors = [];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($errors || !$user) {
            // Don't reveal if email exists or not (security best practice)
            $this->view('auth/forgot-password', [
                'pageTitle' => 'Forgot Password',
                'success' => true,
                'message' => 'If an account exists with that email, a password reset link has been sent.'
            ]);
            return;
        }

        // Create reset token
        $resetModel = new PasswordReset();
        $token = $resetModel->createToken($email);

        // Send email
        $emailSent = EmailHelper::sendPasswordReset($email, $token);

        $this->view('auth/forgot-password', [
            'pageTitle' => 'Forgot Password',
            'success' => true,
            'message' => $emailSent 
                ? 'Password reset link has been sent to your email.' 
                : 'If an account exists with that email, a password reset link has been sent.'
        ]);
    }

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

        // Verify token is valid
        $resetModel = new PasswordReset();
        $reset = $resetModel->findValidToken($token);

        if (!$reset) {
            $this->view('auth/reset-password', [
                'pageTitle' => 'Reset Password',
                'errors' => ['This password reset link is invalid or has expired. Please request a new one.']
            ]);
            return;
        }

        $this->view('auth/reset-password', [
            'pageTitle' => 'Reset Password',
            'token' => $token,
            'email' => $reset['email']
        ]);
    }

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

        // Verify token
        $resetModel = new PasswordReset();
        $reset = $resetModel->findValidToken($token);

        if (!$reset) {
            $errors[] = 'This password reset link is invalid or has expired.';
        }

        if ($errors) {
            $this->view('auth/reset-password', [
                'pageTitle' => 'Reset Password',
                'errors' => $errors,
                'token' => $token
            ]);
            return;
        }

        // Update password
        $userModel = new User();
        $user = $userModel->findByEmail($reset['email']);

        if (!$user) {
            $this->view('auth/reset-password', [
                'pageTitle' => 'Reset Password',
                'errors' => ['User account not found.']
            ]);
            return;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userModel->update($user['id'], ['password_hash' => $passwordHash]);

        // Delete the used token
        $resetModel->deleteToken($token);

        // Redirect to login with success message
        $_SESSION['password_reset_success'] = true;
        $this->redirect(BASE_URL . '/login');
    }

    public function logout(): void
    {
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
            setcookie('remember_user', '', time() - 3600, '/');
        }

        $_SESSION = [];
        if (session_id() !== '' || isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();

        $this->redirect(BASE_URL . '/');
    }
}
