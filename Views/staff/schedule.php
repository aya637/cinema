<?php
// Mock Data is passed from controller (assumed)
// If variables aren't set, define defaults to prevent errors if view is opened directly (though it shouldn't be)
$title = $title ?? 'Staff Schedule';
$days = $days ?? ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
$schedules = $schedules ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?> - CineBook</title>
    <!-- Use standard admin styles -->
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/admin-dashboard.css">
    <style>
        .page-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .schedule-table {
            width: 100%;
            border-collapse: collapse;
        }

        .schedule-table th,
        .schedule-table td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .schedule-table th:first-child,
        .schedule-table td:first-child {
            text-align: left;
            position: sticky;
            left: 0;
            background: #0f172a;
            /* Match typical background to hide scroll */
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }

        .badge-shift {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
        }

        .shift-active {
            background: rgba(16, 185, 129, 0.2);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .shift-off {
            background: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
    </style>
</head>

<body>



    <div class="dashboard">
        <main class="page-container">

            <header style="margin-bottom: 2rem; text-align: center;">
                <h1><?php echo htmlspecialchars($title); ?></h1>
            </header>

            <div class="glassmorphism" style="padding: 0; overflow-x: auto;">
                <table class="schedule-table">
                    <thead>
                        <tr style="background: rgba(30, 41, 59, 0.5);">
                            <th>Employee (Role)</th>
                            <?php foreach ($days as $day): ?>
                                <th><?php echo htmlspecialchars($day); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($schedules as $staff): ?>
                            <tr>
                                <td>
                                    <div style="font-weight: bold; color: #fff;">
                                        <?php echo htmlspecialchars($staff['name']); ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: #9ca3af;">
                                        <?php echo htmlspecialchars($staff['role']); ?>
                                    </div>
                                </td>

                                <?php foreach ($days as $day): ?>
                                    <?php
                                    $shift = $staff['shifts'][$day] ?? 'N/A';
                                    $class = 'badge-shift';
                                    if ($shift === 'Off') {
                                        $class .= ' shift-off';
                                    } elseif ($shift !== 'N/A') {
                                        $class .= ' shift-active';
                                    } else {
                                        $class = ''; // Plain text for N/A
                                    }
                                    ?>
                                    <td>
                                        <span class="<?php echo $class; ?>">
                                            <?php echo htmlspecialchars($shift); ?>
                                        </span>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem; text-align: center;">
                <a href="<?php echo BASE_URL; ?>/public/index.php?url=admin/dashboard" class="btn secondary">
                    &larr; Back to Dashboard
                </a>
            </div>

        </main>
    </div>

</body>

</html>