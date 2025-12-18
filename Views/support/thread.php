<?php
// views/support/thread.php
$ticket = $data['ticket'];
$messages = $ticket['thread'] ?? [];
$errors = $data['errors'] ?? [];
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
            max-width: 1000px;
            margin: 0 auto;
        }

        .thread-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .message-card {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .message-staff {
            border-left: 4px solid #6366f1;
            background: rgba(99, 102, 241, 0.05);
        }

        .message-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 0.75rem;
        }

        .sender-name {
            font-weight: bold;
            color: #fff;
        }

        .sender-staff {
            color: #818cf8;
        }

        .message-date {
            font-size: 0.85rem;
            color: #9ca3af;
        }

        .message-body {
            color: #e2e8f0;
            line-height: 1.6;
            white-space: pre-wrap;
        }

        .reply-box textarea {
            width: 100%;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(148, 163, 184, 0.3);
            color: #fff;
            padding: 1rem;
            border-radius: 0.5rem;
            min-height: 120px;
            font-family: inherit;
        }

        .ticket-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            background: rgba(255, 255, 255, 0.03);
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .meta-item label {
            display: block;
            font-size: 0.8rem;
            color: #9ca3af;
            margin-bottom: 0.25rem;
        }

        .meta-item span {
            color: #fff;
            font-weight: 500;
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

            <div style="margin-bottom: 1rem;">
                <a href="<?php echo BASE_URL; ?>/public/index.php?url=support/index" class="btn secondary btn-small">
                    &larr; Back to Tickets
                </a>
            </div>

            <?php if (!$ticket): ?>
                <div style="text-align: center; padding: 4rem;">
                    <h2>Ticket Not Found</h2>
                    <p>The requested support ticket does not exist.</p>
                </div>
            <?php else: ?>

                <div class="glassmorphism" style="padding: 2rem;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1.5rem;">
                        <div>
                            <h1 style="margin: 0; font-size: 1.8rem;">#<?php echo $ticket['id']; ?>:
                                <?php echo htmlspecialchars($ticket['subject']); ?>
                            </h1>
                        </div>
                        <span style="background: rgba(255,255,255,0.1); padding: 0.5rem 1rem; border-radius: 8px;">
                            <?php echo htmlspecialchars(ucfirst($ticket['status'])); ?>
                        </span>
                    </div>

                    <div class="ticket-meta">
                        <div class="meta-item">
                            <label>Requester</label>
                            <span><?php echo htmlspecialchars($ticket['requester_name']); ?></span>
                        </div>
                        <div class="meta-item">
                            <label>Email</label>
                            <span><?php echo htmlspecialchars($ticket['requester_email']); ?></span>
                        </div>
                        <div class="meta-item">
                            <label>Priority</label>
                            <span style="color: <?php echo $ticket['priority'] == 'High' ? '#fca5a5' : '#fcd34d'; ?>">
                                <?php echo htmlspecialchars($ticket['priority']); ?>
                            </span>
                        </div>
                        <div class="meta-item">
                            <label>Created</label>
                            <span><?php echo date('M d, Y H:i', strtotime($ticket['created_at'])); ?></span>
                        </div>
                    </div>

                    <div
                        style="margin-bottom: 2rem; background: rgba(0,0,0,0.2); padding: 1.5rem; border-radius: 0.5rem; border-left: 4px solid #ec4899;">
                        <h3 style="margin-top: 0; margin-bottom: 0.5rem; font-size: 1rem; color: #cbd5f5;">Original
                            Description:</h3>
                        <div class="message-body">
                            <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                        </div>
                    </div>

                    <h3
                        style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 1rem; margin-bottom: 1.5rem;">
                        Discussion Thread</h3>

                    <div class="thread-container">
                        <?php if (empty($messages)): ?>
                            <p style="text-align: center; color: #9ca3af;">No replies yet.</p>
                        <?php else: ?>
                            <?php foreach ($messages as $msg): ?>
                                <div class="message-card <?php echo $msg['is_staff'] ? 'message-staff' : ''; ?>">
                                    <div class="message-header">
                                        <span class="sender-name <?php echo $msg['is_staff'] ? 'sender-staff' : ''; ?>">
                                            <?php echo htmlspecialchars($msg['sender_name']); ?>
                                            <?php if ($msg['is_staff'])
                                                echo '<span style="font-size:0.7em; background:#6366f1; color:white; padding:0.1rem 0.4rem; border-radius:4px; margin-left:0.5rem;">STAFF</span>'; ?>
                                        </span>
                                        <span class="message-date">
                                            <?php echo date('M d, g:i A', strtotime($msg['created_at'])); ?>
                                        </span>
                                    </div>
                                    <div class="message-body"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Reply Form -->
                    <div class="glassmorphism"
                        style="padding: 1.5rem; margin-top: 2rem; border-color: rgba(99, 102, 241, 0.3);">
                        <h3 style="margin-top: 0; margin-bottom: 1rem;">Post specific Reply</h3>

                        <?php if (!empty($errors)): ?>
                            <div style="color: #fca5a5; margin-bottom: 1rem;">
                                <?php foreach ($errors as $e)
                                    echo $e . '<br>'; ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo BASE_URL; ?>/public/index.php?url=support/reply" method="POST">
                            <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">

                            <div class="reply-box">
                                <textarea name="message" placeholder="Type your reply here..." required></textarea>
                            </div>

                            <div
                                style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <label style="font-size: 0.9rem; color: #cbd5f5;">Update Status:</label>
                                    <select name="new_status"
                                        style="background: rgba(15,23,42,0.8); border: 1px solid rgba(148,163,184,0.3); color: white; padding: 0.3rem; border-radius: 4px;">
                                        <option value="">Do not change</option>
                                        <option value="Open">Open</option>
                                        <option value="Closed">Closed</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn primary">Send Reply</button>
                            </div>
                        </form>
                    </div>

                </div>
            <?php endif; ?>

        </main>
    </div>

</body>

</html>