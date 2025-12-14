<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title'] ?? 'Staff Management'); ?> - CineBook</title>
    <!-- Use standard admin styles -->
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/admin-dashboard.css">
    <style>
        /* Add minimal overrides if necessary to match 'Staff' context if admin css is too specific */
        .page-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <!-- Reuse Admin Header Structure -->


    <div class="dashboard">
        <main class="page-container">
<header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1><?php echo htmlspecialchars($data['title'] ?? 'Staff Management'); ?></h1>
        <p style="color: #cbd5f5;">Manage your cinema staff members</p>
    </div>

    <div style="display: flex; gap: 10px;">
        <a href="<?php echo BASE_URL; ?>/public/index.php?url=admin" class="btn secondary" 
           style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.2); color: #e5e7eb; display: inline-flex; align-items: center;">
            &larr; Back to Dashboard
        </a>

        <a href="<?php echo BASE_URL; ?>/public/index.php?url=staff/add" class="btn primary">
            + Add New Staff Member
        </a>
    </div>
</header>

            <?php if (isset($_GET['status'])): ?>
                <?php
                $statusMsg = '';
                $statusColor = '';
                if ($_GET['status'] === 'success') {
                    $statusMsg = 'Staff member saved successfully!';
                    $statusColor = 'background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.4);';
                } elseif ($_GET['status'] === 'removed') {
                    $statusMsg = 'Staff member removed successfully!';
                    $statusColor = 'background: rgba(245, 158, 11, 0.2); color: #fcd34d; border: 1px solid rgba(245, 158, 11, 0.4);';
                }
                ?>
                <?php if ($statusMsg): ?>
                    <div style="padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; <?php echo $statusColor; ?>">
                        <?php echo $statusMsg; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="glassmorphism" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th style="padding: 1rem; text-align: left;">Name</th>
                            <th style="padding: 1rem; text-align: left;">Email</th>
                            <th style="padding: 1rem; text-align: left;">Role</th>
                            <th style="padding: 1rem; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($data['staff'])): ?>
                            <tr>
                                <td colspan="4" style="padding: 2rem; text-align: center; color: #9ca3af;">No staff members
                                    found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($data['staff'] as $staff): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 1rem; color: #fff; font-weight: 500;">
                                        <?php echo htmlspecialchars($staff['name']); ?>
                                    </td>
                                    <td style="padding: 1rem; color: #cbd5f5;">
                                        <?php echo htmlspecialchars($staff['email']); ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <span
                                            style="background: rgba(99, 102, 241, 0.2); color: #a5b4fc; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.85rem; border: 1px solid rgba(99, 102, 241, 0.3);">
                                            <?php echo htmlspecialchars($staff['role']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <a href="<?php echo BASE_URL; ?>/public/index.php?url=staff/edit&id=<?php echo urlencode($staff['id']); ?>"
                                            class="btn secondary btn-small" style="margin-right: 0.5rem;">
                                            Edit
                                        </a>

                                        <a href="<?php echo BASE_URL; ?>/public/index.php?url=staff/delete&id=<?php echo urlencode($staff['id']); ?>"
                                            class="btn delete btn-small"
                                            style="background: rgba(239, 68, 68, 0.2); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.4);"
                                            onclick="return confirm('Are you sure you want to delete this staff member?');">
                                            Remove
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

</body>

</html>