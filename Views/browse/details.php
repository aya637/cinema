<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']) ?> - Details</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/movie.css">
    
</head>
<body>
    <main>
        <section class="details-hero">
            <div class="cb-container">
                
                <a href="<?= BASE_URL ?>/public/movies" class="btn-outline" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border-radius: 8px; margin-bottom: 2rem; font-size: 0.9rem;">
                    &larr; Back to Movies
                </a>

                <div class="details-grid">
<div class="poster-wrapper">
    <?php 
        $posterUrl = '';
        
        if (!empty($movie['poster'])) {
            if (filter_var($movie['poster'], FILTER_VALIDATE_URL)) {
                $posterUrl = $movie['poster'];
            } 
            elseif (strpos($movie['poster'], 'uploads/') === 0) {
                $posterUrl = BASE_URL . '/public/' . $movie['poster'];
            }
            else {
                $posterUrl = BASE_URL . '/public/images/' . $movie['poster'];
            }
        }
        if (empty($posterUrl)) {
            $posterUrl = 'https://placehold.co/400x600/1f2937/ffffff?text=No+Poster';
        }
    ?>
    <img 
        src="<?= htmlspecialchars($posterUrl) ?>" 
        alt="<?= htmlspecialchars($movie['title']) ?>" 
        class="poster-img">
</div>
        
                    <div class="info-wrapper">
                        <?php if(!empty($movie['tagline'])): ?>
                            <div class="movie-tagline"><?= htmlspecialchars($movie['tagline']) ?></div>
                        <?php endif; ?>
                        
                        <h1 class="movie-h1"><?= htmlspecialchars($movie['title']) ?></h1>

                        <div class="meta-row">
                            <?php if(!empty($movie['rating'])): ?>
                                <div class="meta-badge"><span class="rating-star">★</span> <?= htmlspecialchars($movie['rating']) ?> </div>
                            <?php endif; ?>
                            
                            <?php if(!empty($movie['duration_minutes'])): ?>
                                <div class="meta-badge">⏱ <?= htmlspecialchars($movie['duration_minutes']) ?> min</div>
                            <?php endif; ?>

                            <?php if(!empty($movie['genre'])): ?>
                                <div class="meta-badge">🎬 <?= htmlspecialchars($movie['genre']) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="synopsis-box">
                            <span class="synopsis-label">Synopsis</span>
                            <p class="synopsis-text">
                                <?= nl2br(htmlspecialchars($movie['description'] ?? 'No description available.')) ?>
                            </p>
                        </div>

                        <div style="display: flex; gap: 1rem;">
                            <button class="btn btn-outline">Watch Trailer</button>
                            <a href="#showtimes" class="btn btn-primary" id="book-tickets-btn">Book Tickets ↓</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="showtimes" class="showtimes-section">
            <div class="cb-container">
                <h2 class="section-h2"><span>🎟</span> Available Showtimes</h2>

                <?php if (empty($showtimes)): ?>
                    <div class="empty-state">
                        <h3>No showtimes scheduled</h3>
                        <p>We are currently updating our schedule. Please check back later!</p>
                    </div>
                <?php else: ?>
                    
                    <?php 
                    // Logic to group showtimes by Date
                    $showtimes_by_date = [];
                    foreach ($showtimes as $show) {
                        $date = date('Y-m-d', strtotime($show['show_time']));
                        $showtimes_by_date[$date][] = $show;
                    }

                    // Loop through dates
                    foreach ($showtimes_by_date as $date => $shows): 
                        $prettyDate = date('l, F jS', strtotime($date));
                    ?>
                        <div class="date-group">
                            <div class="date-header"><?= $prettyDate ?></div>
                            
                            <div class="time-grid">
                                <?php foreach ($shows as $show): 
                                    $time = date('g:i A', strtotime($show['show_time']));
                                    $price = number_format($show['base_price'] ?? 10.00, 2);
                                ?>
                                    <a href="<?= BASE_URL ?>/public/booking/selectSeats/<?= $show['show_id'] ?>" class="showtime-card">
                                        <span class="st-time"><?= $time ?></span>
                                        <span class="st-screen"><?= htmlspecialchars($show['screen_name'] ?? 'Main Hall') ?></span>
                                        <span class="st-price">From $<?= $price ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        // Smooth scroll to showtimes when clicking "Book Tickets"
        (function() {
            const btn = document.getElementById('book-tickets-btn');
            const target = document.getElementById('showtimes');
            if (btn && target) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const headerOffset = 80; // adjust if header height changes
                    const rect = target.getBoundingClientRect();
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const targetY = rect.top + scrollTop - headerOffset;
                    window.scrollTo({ top: targetY, behavior: 'smooth' });
                });
            }
        })();
    </script>
</body>
</html>