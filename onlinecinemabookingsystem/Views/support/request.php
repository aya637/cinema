<?php
// views/support/request.php
$errors = $data['errors'] ?? [];
$old = $data['old'] ?? [];
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
            max-width: 800px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            color: #d1d5db;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.8rem 1rem;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 0.5rem;
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }

        textarea.form-control {
            min-height: 150px;
            resize: vertical;
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
                <a href="<?php echo BASE_URL; ?>/public/index.php?url=support/index">Support Tickets</a>
                <a href="<?php echo BASE_URL; ?>/public/index.php?action=logout">Log out</a>
            </nav>
        </div>
    </header>

    <div class="dashboard">
        <main class="page-container">

            <header style="margin-bottom: 2rem; text-align: center;">
                <h1 style="margin-bottom: 0.5rem;">Submit a Request</h1>
                <p style="color: #9ca3af;">How can we help you today?</p>
            </header>

            <div class="glassmorphism" style="padding: 2.5rem;">

                <?php if (!empty($errors)): ?>
                    <div
                        style="background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 1rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                        <ul style="margin: 0; padding-left: 1.5rem;">
                            <?php foreach ($errors as $error)
                                echo "<li>$error</li>"; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>/public/index.php?url=support/store" method="POST">

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control"
                            placeholder="Brief summary of the issue"
                            value="<?php echo htmlspecialchars($old['subject'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" class="form-control">
                            <option value="Low" <?php echo ($old['priority'] ?? '') === 'Low' ? 'selected' : ''; ?>>Low -
                                General Inquiry</option>
                            <option value="Medium" <?php echo ($old['priority'] ?? 'Medium') === 'Medium' ? 'selected' : ''; ?>>Medium - Feature Request / Minor Issue</option>
                            <option value="High" <?php echo ($old['priority'] ?? '') === 'High' ? 'selected' : ''; ?>>High
                                - Critical Bug / Payment Issue</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" class="form-control"
                            placeholder="Please describe your issue in detail..."
                            required><?php echo htmlspecialchars($old['description'] ?? ''); ?></textarea>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn primary"
                            style="flex: 1; justify-content: center; padding: 0.8rem;">
                            Submit Request
                        </button>
                        <a href="<?php echo BASE_URL; ?>/public/index.php?url=support/index" class="btn secondary">
                            Cancel
                        </a>
                    </div>

                </form>
            </div>

        </main>
    </div>

</body>

</html>