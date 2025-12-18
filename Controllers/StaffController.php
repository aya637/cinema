<?php
// app/controllers/StaffController.php

class StaffController extends Controller
{
    protected $staffModel;

    public function __construct()
    {
        $this->staffModel = new Staff();
    }

    /**
     * Default route /staff
     */
    public function index()
    {
        $this->list();
    }

    /**
     * GET /staff/list
     */
    public function list()
    {
        $staff_members = $this->staffModel->getAll(1, 100, 'all');

        $this->view('staff/list', [
            'title' => 'Manage Staff',
            'staff' => $staff_members
        ]);
    }

    /**
     * GET /staff/add
     */
    public function add()
    {
        // No staff data because this is a NEW record
        $this->view('staff/add', [
            'title' => 'Add New Staff Member',
            'staff' => [],
            'errors' => [],
            'old_input' => []
        ]);
    }

    /**
     * GET /staff/edit?id=1
     */
    public function edit()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            $this->view('error/404', ['message' => 'Invalid staff ID']);
            return;
        }

        $staff = $this->staffModel->getById($_GET['id']);

        if (!$staff) {
            $this->view('error/404', ['message' => 'Staff member not found']);
            return;
        }

        // Use the exact same view: add.php
        $this->view('staff/add', [
            'title' => 'Edit Staff Member',
            'staff' => $staff,
            'errors' => [],
            'old_input' => []
        ]);
    }

    /**
     * POST /staff/save
     */
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/public/index.php?url=staff/list');
            return;
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role = trim($_POST['role'] ?? 'Staff');
        $password = $_POST['password'] ?? '';

        $errors = [];

        if ($name === '')
            $errors[] = 'Full name is required.';
        if ($email === '')
            $errors[] = 'Email is required.';

        // Validate email format
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email address.';
        }

        // Check for duplicate email
        if ($email !== '' && $this->staffModel->emailExists($email, $id)) {
            $errors[] = 'This email address is already registered. Please use a different email.';
        }

        if (!$id && $password === '') {
            $errors[] = 'Password is required for new staff.';
        }

        if (!empty($errors)) {
            // Return to form with errors
            $this->view('staff/add', [
                'title' => $id ? 'Edit Staff Member' : 'Add New Staff Member',
                'errors' => $errors,
                'staff' => ['id' => $id],
                'old_input' => $_POST
            ]);
            return;
        }

        // UPDATE existing
        if ($id) {
            $saved = $this->staffModel->update($id, $name, $email, $role, $password ?: null);
        }
        // CREATE new
        else {
            $saved = $this->staffModel->add($name, $email, $password, $role);
        }

        if ($saved) {
            header('Location: ' . BASE_URL . '/public/index.php?url=staff/list&status=success');
            exit;
        }

        // Save failure
        $this->view('staff/add', [
            'title' => $id ? 'Edit Staff Member' : 'Add New Staff Member',
            'errors' => ['Database operation failed. Please check the error logs for details.'],
            'staff' => ['id' => $id],
            'old_input' => $_POST
        ]);
    }

    /**
     * GET /staff/delete?id=1
     */
    public function delete()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: ' . BASE_URL . '/public/index.php?url=staff/list&status=fail');
            return;
        }

        $deleted = $this->staffModel->delete($_GET['id']);

        header('Location: ' . BASE_URL . '/public/index.php?url=staff/list&status=' . ($deleted ? 'removed' : 'fail'));
    }
}
