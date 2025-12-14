<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController extends Controller
{
    public function index(): void
    {
        // Check if user is logged in
        if (empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/public/index.php?url=login');
            return;
        }

        $userModel = new User();
        $userId = (int) $_SESSION['user_id'];
        $user = $userModel->findById($userId);

        if (!$user) {
            // If user is missing in DB, force logout
            $_SESSION = [];
            $this->redirect(BASE_URL . '/public/index.php?url=login');
            return;
        }

        // Get bookings (placeholder for now - you can implement this later)
        $currentBookings = [];
        $pastBookings = [];
        
        // TODO: Uncomment when you have booking functionality
        // $bookingModel = new Booking();
        // $currentBookings = $bookingModel->getUserBookings($userId, 'upcoming');
        // $pastBookings = $bookingModel->getUserBookings($userId, 'past');

        $this->view('profile/index', [
            'pageTitle' => 'Your Profile',
            'user' => $user,
            'currentBookings' => $currentBookings,
            'pastBookings' => $pastBookings,
            'profileMessage' => $_SESSION['profile_message'] ?? null,
            'profileMessageType' => $_SESSION['profile_message_type'] ?? null,
        ]);

        // Clear flash messages
        unset($_SESSION['profile_message'], $_SESSION['profile_message_type']);
    }

    public function update(): void
    {
        // Check if user is logged in
        if (empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/public/index.php?url=login');
            return;
        }

        $userModel = new User();
        $userId = (int) $_SESSION['user_id'];
        $user = $userModel->findById($userId);

        if (!$user) {
            $this->redirect(BASE_URL . '/public/index.php?url=login');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        // Validate name
        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please provide a valid email address.';
        }

        // Validate passwords if provided
        if ($newPassword !== '' || $confirmPassword !== '') {
            if (strlen($newPassword) < 8) {
                $errors[] = 'New password must be at least 8 characters.';
            }
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'New password and confirmation do not match.';
            }
        }

        // Check if email is taken by another user
        $existing = $userModel->findByEmail($email);
        if ($existing && (int) $existing['id'] !== $userId) {
            $errors[] = 'This email is already used by another account.';
        }

        if ($errors) {
            $_SESSION['profile_message'] = implode(' ', $errors);
            $_SESSION['profile_message_type'] = 'error';
            $this->redirect(BASE_URL . '/public/index.php?url=profile');
            return;
        }

        // Prepare update data
        $data = [
            'name' => $name,
            'email' => $email,
        ];

        // Add password to update if provided
        if ($newPassword !== '') {
            $data['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        // Update user in database
        $updateSuccess = $userModel->update($userId, $data);

        if ($updateSuccess) {
            // Update session data
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            $_SESSION['profile_message'] = 'Your profile has been updated successfully.';
            $_SESSION['profile_message_type'] = 'success';
        } else {
            $_SESSION['profile_message'] = 'Failed to update profile. Please try again.';
            $_SESSION['profile_message_type'] = 'error';
        }

        $this->redirect(BASE_URL . '/public/index.php?url=profile');
    }
}
