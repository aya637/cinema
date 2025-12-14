<?php
// views/support/tickets.php
$tickets = $data['tickets'] ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($data['title']); ?> - CineBook</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/admin-dashboard.css">
    <style>
        .page-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            text-transform: capitalize;
        }

        .status-new {
            background: rgba(59, 130, 246, 0.2);
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.4);
        }

        .status-open {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .status-closed {
            background: rgba(107, 114, 128, 0.2);
            color: #d1d5db;
            border: 1px solid rgba(107, 114, 128, 0.4);
        }

        .priority-high {
            color: #fca5a5;
        }

        .priority-medium {
            color: #fcd34d;
        }

        .priority-low {
            color: #93c5fd;
        }
    </style>
</head>

<body>

    <header class="navbar">
        <div class="nav-container">
            <div class="logo">
                <span class="cine">Cine</span><span class="book">Book</span>
            </div>

            <nav class="nav-links">
                <a href="<?php echo BASE_URL; ?>/public/index.php">Home</a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?url=admin/dashboard">Admin Dashboard</a>
                <a href="#" class="active">Support Tickets</a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?action=logout">Log out</a>
            </nav>

            <div class="nav-icons">
                <div class="profile-icon">A</div>
            </div>
        </div>
    </header>

    <div class="dashboard">
        <main class="page-container">

            <header style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1>Support Tickets</h1>
                    <p style="color: #cbd5f5;">Manage customer inquiries and issues</p>
                </div>

                <a href="<?php echo BASE_URL; ?>/public/index.php?url=support/create" class="btn primary">
                    + New Ticket
                </a>
            </header>

            <div class="glassmorphism" style="padding: 0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                            <th style="padding: 1rem; text-align: left;">ID</th>
                            <th style="padding: 1rem; text-align: left;">Subject</th>
                            <th style="padding: 1rem; text-align: left;">Requester</th>
                            <th style="padding: 1rem; text-align: center;">Priority</th>
                            <th style="padding: 1rem; text-align: center;">Status</th>
                            <th style="padding: 1rem; text-align: right;">Created</th>
                            <th style="padding: 1rem; text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($tickets)): ?>
                            <tr>
                                <td colspan="7" style="padding: 2rem; text-align: center; color: #9ca3af;">No tickets found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($tickets as $ticket): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 1rem; color: #9ca3af;">#<?php echo $ticket['id']; ?></td>
                                    <td style="padding: 1rem; font-weight: 500; color: #fff;">
                                        <?php echo htmlspecialchars($ticket['subject']); ?>
                                    </td>
                                    <td style="padding: 1rem; color: #cbd5f5;">
                                        <?php echo htmlspecialchars($ticket['requester']); ?>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <span class="priority-<?php echo strtolower($ticket['priority']); ?>">
                                            <?php echo htmlspecialchars($ticket['priority']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <span class="status-badge status-<?php echo strtolower($ticket['status']); ?>">
                                            <?php echo htmlspecialchars($ticket['status']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem; text-align: right; color: #9ca3af; font-size: 0.9rem;">
                                        <?php echo date('M d, Y', strtotime($ticket['created_at'])); ?>
                                    </td>
                                    <td style="padding: 1rem; text-align: center;">
                                        <a href="<?php echo BASE_URL; ?>/public/index.php?url=support/thread&id=<?php echo $ticket['id']; ?>"
                                            class="btn secondary btn-small">
                                            View
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