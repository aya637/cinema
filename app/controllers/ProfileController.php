<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/login');
        }

        $userModel = new User();
        $userId = (int) $_SESSION['user_id'];
        $user = $userModel->findById($userId);

        if (!$user) {
            // If user is missing in DB, force logout
            $_SESSION = [];
            $this->redirect(BASE_URL . '/login');
        }

        $this->view('profile/index', [
            'pageTitle' => 'Your Profile',
            'user' => $user,
            'profileMessage' => $_SESSION['profile_message'] ?? null,
            'profileMessageType' => $_SESSION['profile_message_type'] ?? null,
        ]);

        unset($_SESSION['profile_message'], $_SESSION['profile_message_type']);
    }

    public function update(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->redirect(BASE_URL . '/login');
        }

        $userModel = new User();
        $userId = (int) $_SESSION['user_id'];
        $user = $userModel->findById($userId);

        if (!$user) {
            $this->redirect(BASE_URL . '/login');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please provide a valid email address.';
        }

        if ($newPassword !== '' || $confirmPassword !== '') {
            if (strlen($newPassword) < 8) {
                $errors[] = 'New password must be at least 8 characters.';
            }
            if ($newPassword !== $confirmPassword) {
                $errors[] = 'New password and confirmation do not match.';
            }
        }

        // Check for email taken by another user
        $existing = $userModel->findByEmail($email);
        if ($existing && (int) $existing['id'] !== $userId) {
            $errors[] = 'This email is already used by another account.';
        }

        if ($errors) {
            $_SESSION['profile_message'] = implode(' ', $errors);
            $_SESSION['profile_message_type'] = 'error';
            $this->redirect(BASE_URL . '/profile');
        }

        $data = [
            'name' => $name,
            'email' => $email,
        ];

        if ($newPassword !== '') {
            $data['password_hash'] = password_hash($newPassword, PASSWORD_DEFAULT);
        }

        $userModel->update($userId, $data);

        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        $_SESSION['profile_message'] = 'Your profile has been updated.';
        $_SESSION['profile_message_type'] = 'success';

        $this->redirect(BASE_URL . '/profile');
    }
}


