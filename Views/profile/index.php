<section class="profile-page">
    <div class="cb-container">
        <div class="profile-hero">
            <div class="profile-avatar">
                <span><?= strtoupper(substr($user['name'], 0, 1)) ?></span>
            </div>
            <div>
                <div class="profile-badge">Welcome back</div>
                <h1 class="profile-title"><?= htmlspecialchars($user['name']) ?></h1>
                <p class="profile-subtitle">
                    This is your Screenwave profile. Manage your account details and keep track of your cinema journey.
                </p>
            </div>
        </div>

        <?php if (!empty($profileMessage)): ?>
            <div class="profile-alert profile-<?= htmlspecialchars($profileMessageType ?? 'info') ?>">
                <?= htmlspecialchars($profileMessage) ?>
            </div>
        <?php endif; ?>

        <div class="profile-grid">
            <div class="profile-card main">
                <h2>Account details</h2>
                <form method="post" action="<?= BASE_URL ?>/public/index.php?url=profile" class="profile-form">
                    <div class="profile-row">
                        <span class="label">Name</span>
                        <span class="value">
                            <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
                        </span>
                    </div>
                    <div class="profile-row">
                        <span class="label">Email</span>
                        <span class="value">
                            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </span>
                    </div>
                    <?php if (!empty($user['created_at'])): ?>
                        <div class="profile-row">
                            <span class="label">Member since</span>
                            <span class="value"><?= htmlspecialchars(date('F j, Y', strtotime($user['created_at']))) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="profile-row">
                        <span class="label">New password</span>
                        <span class="value">
                            <input type="password" name="new_password" placeholder="Leave blank to keep current">
                        </span>
                    </div>
                    <div class="profile-row">
                        <span class="label">Confirm new password</span>
                        <span class="value">
                            <input type="password" name="confirm_password" placeholder="Repeat new password">
                        </span>
                    </div>
                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>

            <div class="profile-card side">
                <h3>Quick Stats</h3>
                <p class="profile-text">
                    You have <strong><?= count($currentBookings) ?></strong> upcoming booking<?= count($currentBookings) !== 1 ? 's' : '' ?>
                    and <strong><?= count($pastBookings) ?></strong> completed booking<?= count($pastBookings) !== 1 ? 's' : '' ?>.
                </p>
                <a href="<?= BASE_URL ?>/public/movies" class="btn btn-primary full-width">Browse Movies</a>
                <a href="<?= BASE_URL ?>/public/index.php?url=logout" class="btn btn-outline full-width" style="margin-top:0.5rem;">Sign out</a>
            </div>
        </div>
    </div>
</section>

<section class="profile-bookings cb-container">
    <?php
        $currCount = count($currentBookings);
        $pastCount = count($pastBookings);
    ?>
    <div class="profile-card">
        <div class="profile-bookings-head">
            <h2 class="section-title" style="text-align:left;margin:0;">Your Bookings</h2>
            <div class="booking-tabs" role="tablist" aria-label="Bookings tabs">
                <button class="booking-tab active" data-target="current" role="tab" aria-selected="true">
                    Current <span class="tab-count"><?= $currCount ?></span>
                </button>
                <button class="booking-tab" data-target="previous" role="tab" aria-selected="false">
                    Previous <span class="tab-count"><?= $pastCount ?></span>
                </button>
            </div>
        </div>

        <div class="booking-panels">
            <div class="booking-panel" data-panel="current">
                <?php if ($currCount === 0): ?>
                    <p class="profile-text" style="text-align: center; padding: 2rem; color: #9ca3af;">
                        You have no upcoming bookings. <a href="<?= BASE_URL ?>/public/movies" style="color: #a5b4fc;">Browse movies</a> to book your next cinema experience.
                    </p>
                <?php else: ?>
                    <ul class="booking-list">
                        <?php foreach ($currentBookings as $b): ?>
                            <li class="booking-item">
                                <div style="flex: 1;">
                                    <div class="booking-title"><?= htmlspecialchars($b['title']) ?></div>
                                    <div class="booking-meta">
                                        📅 <?= htmlspecialchars($b['showtime']) ?><br>
                                        💺 Seats: <?= htmlspecialchars($b['seats']) ?><br>
                                        🎬 <?= htmlspecialchars($b['screen']) ?><br>
                                        💰 $<?= number_format($b['amount'], 2) ?>
                                    </div>
                                </div>
                                <div class="booking-actions">
                                    <span class="btn btn-small" style="background: rgba(34, 197, 94, 0.2); color: #6ee7b7; border: 1px solid rgba(34, 197, 94, 0.4); cursor: default;">
                                        Confirmed
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="booking-panel" data-panel="previous" style="display:none;">
                <?php if ($pastCount === 0): ?>
                    <p class="profile-text" style="text-align: center; padding: 2rem; color: #9ca3af;">
                        No past bookings yet. Your booking history will appear here after you attend your shows.
                    </p>
                <?php else: ?>
                    <ul class="booking-list">
                        <?php foreach ($pastBookings as $b): ?>
                            <li class="booking-item">
                                <div style="flex: 1;">
                                    <div class="booking-title"><?= htmlspecialchars($b['title']) ?></div>
                                    <div class="booking-meta">
                                        📅 <?= htmlspecialchars($b['showtime']) ?><br>
                                        💺 Seats: <?= htmlspecialchars($b['seats']) ?><br>
                                        🎬 <?= htmlspecialchars($b['screen']) ?><br>
                                        💰 $<?= number_format($b['amount'], 2) ?><br>
                                        🎟️ Ref: <?= htmlspecialchars($b['payment_ref']) ?>
                                    </div>
                                </div>
                                <div class="booking-actions">
                                    <span class="btn btn-small btn-ghost" style="opacity: 0.6; cursor: default;">
                                        Completed
                                    </span>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
// Simple tabs for bookings
(function(){
    const tabs = Array.from(document.querySelectorAll('.booking-tab'));
    const panels = Array.from(document.querySelectorAll('.booking-panel'));
    if (tabs.length === 0) return;
    tabs.forEach(t => t.addEventListener('click', function(){
        tabs.forEach(x => { x.classList.remove('active'); x.setAttribute('aria-selected','false'); });
        t.classList.add('active'); t.setAttribute('aria-selected','true');
        const target = t.getAttribute('data-target');
        panels.forEach(p => {
            if (p.getAttribute('data-panel') === target) {
                p.style.display = '';
            } else {
                p.style.display = 'none';
            }
        });
    }));
})();
</script>