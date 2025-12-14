<?php
/**
 * View: app/views/staff/add.php
 * Used for both ADD and EDIT staff
 */

if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://localhost/app');
}

$staff = $data['staff'] ?? [];
$errors = $data['errors'] ?? [];
$old_input = $data['old_input'] ?? [];

$is_editing = isset($staff['id']) && $staff['id'] > 0;

$title = $is_editing
    ? 'Edit Staff Member: ' . htmlspecialchars($staff['name'] ?? '')
    : 'Add New Staff Member';

function get_value($key, $staff, $old_input)
{
    if (isset($old_input[$key])) {
        return htmlspecialchars($old_input[$key]);
    }
    return htmlspecialchars($staff[$key] ?? '');
}

function selected_option($value, $option)
{
    return ($value === $option) ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - CineBook</title>
    <!-- Use standard admin styles -->
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/admin-dashboard.css">
    <style>
        .page-container {
            padding: 2rem;
            max-width: 600px;
            /* Narrower for forms */
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #e5e7eb;
            font-size: 0.9rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.2);
            color: #fff;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }
    </style>
</head>

<body>



    <div class="dashboard">
        <main class="page-container">

            <div class="glassmorphism" style="padding: 2rem;">
                <h1 style="margin-top: 0; margin-bottom: 2rem; text-align: center;"><?= $title ?></h1>

                <?php if (!empty($errors)): ?>
                    <div
                        style="margin-bottom: 2rem; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 1rem; border-radius: 0.5rem;">
                        <strong style="display: block; margin-bottom: 0.5rem;">Please fix the following:</strong>
                        <ul style="margin: 0; padding-left: 1.5rem;">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo BASE_URL; ?>/public/index.php?url=staff/save">

                    <?php if ($is_editing): ?>
                        <input type="hidden" name="id" value="<?= htmlspecialchars($staff['id']) ?>">
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required
                            value="<?= get_value('name', $staff, $old_input) ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email (Login ID)</label>
                        <input type="email" id="email" name="email" required
                            value="<?= get_value('email', $staff, $old_input) ?>">
                    </div>

                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <?php $role_value = get_value('role', $staff, $old_input); ?>
                            <option value="Staff" <?= selected_option($role_value, 'Staff') ?>>Staff</option>
                            <option value="Manager" <?= selected_option($role_value, 'Manager') ?>>Manager</option>
                            <option value="Admin" <?= selected_option($role_value, 'Admin') ?>>Administrator</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password"
                            placeholder="<?= $is_editing ? 'Leave blank to keep current password' : 'Enter password' ?>"
                            <?= !$is_editing ? 'required' : '' ?>>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn primary" style="flex: 1; justify-content: center;">
                            <?= $is_editing ? 'Update Staff Member' : 'Create Staff Member' ?>
                        </button>

                        <a href="<?php echo BASE_URL; ?>/public/index.php?url=staff/list" class="btn secondary"
                            style="background: rgba(148, 163, 184, 0.1); border: 1px solid rgba(148, 163, 184, 0.2);">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>

        </main>
    </div>

</body>

</html>