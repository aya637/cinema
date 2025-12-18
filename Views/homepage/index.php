<?php
// app/views/homepage/index.php
// UPDATED VERSION - Uses movies table for carousel and keeps carousel styling
?>
<section class="hero">
    <div class="cb-container hero-inner">
        <div class="hero-copy">
            <p class="hero-kicker">Welcome to <?= APP_NAME ?></p>
            <h1 class="hero-title">Experience Cinema Like Never Before</h1>
            <p class="hero-subtitle">
                Book your favorite movies, choose the perfect seats, and enjoy premium
                entertainment at <?= APP_NAME ?> theaters.
            </p>
            <div class="hero-actions">
                <!-- UPDATED: Book Now button links to movies page -->
                <a href="<?= BASE_URL ?>/public/movies" class="btn btn-primary">Book Now</a>
                <!-- UPDATED: View Showtimes button links to movies page -->
                <a href="<?= BASE_URL ?>/public/movies" class="btn btn-outline">View Showtimes</a>
            </div>
        </div>
        <div class="hero-art">
            <div class="hero-card hero-card-main">
                <img src="<?= BASE_URL ?>/images/hero.jpg" alt="Main hero poster" class="hero-card-img">
            </div>
            <div class="hero-card hero-card-secondary">
                <img src="<?= BASE_URL ?>/images/hero2.jpeg" alt="Secondary hero poster" class="hero-card-img">
            </div>
        </div>
    </div>
</section>

<?php if (!empty($movies) && is_array($movies)): ?>
    <script>
    // Carousel initialization script - runs after DOM is ready
    (function(){
        function initCarousel(){
            const root = document.getElementById('nowShowingCarousel');
            if (!root) return;
            const track = root.querySelector('.carousel-track');
            const slides = Array.from(root.querySelectorAll('.carousel-slide'));
            const prev = root.querySelector('.carousel-prev');
            const next = root.querySelector('.carousel-next');
            const dotsWrap = root.querySelector('.carousel-dots');
            if (!track || slides.length === 0) return;

            let index = 0;

            function renderDots() {
                if (!dotsWrap) return;
                dotsWrap.innerHTML = '';
                slides.forEach((s,i)=>{
                    const d = document.createElement('button');
                    d.className = 'carousel-dot' + (i===index? ' active':'');
                    d.addEventListener('click', ()=>{ goTo(i); });
                    dotsWrap.appendChild(d);
                });
            }

            function update() {
                if (slides.length === 0) return;
                const gapValue = parseFloat(getComputedStyle(track).gap || 16) || 16;
                const slideWidth = slides[0].getBoundingClientRect().width + gapValue;
                const x = -index * slideWidth;
                track.style.transform = `translateX(${x}px)`;
                if (dotsWrap) {
                    const dots = dotsWrap.querySelectorAll('.carousel-dot');
                    dots.forEach((d,i)=> d.classList.toggle('active', i===index));
                }
            }

            function goTo(i) { index = Math.max(0, Math.min(slides.length-1, i)); update(); }
            if (prev) prev.addEventListener('click', ()=> goTo(index-1));
            if (next) next.addEventListener('click', ()=> goTo(index+1));

            // initialize
            renderDots();
            window.addEventListener('resize', update);
            // optional: autoplay
            let autoplay = true; let autoplayMs = 4000; let timer = null;
            function startAuto(){ if(!autoplay || slides.length===0) return; timer = setInterval(()=>{ index = (index+1) % slides.length; update(); }, autoplayMs); }
            function stopAuto(){ if(timer) clearInterval(timer); timer = null; }
            root.addEventListener('mouseenter', stopAuto);
            root.addEventListener('mouseleave', startAuto);
            update(); startAuto();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initCarousel);
        } else {
            initCarousel();
        }
    })();
    </script>
<?php endif; ?>

<section id="now-showing" class="now-showing">
    <div class="cb-container">
        <h2 class="section-title">Now Showing</h2>
        
        <?php if (!empty($movies) && is_array($movies)): ?>
            <div class="carousel" id="nowShowingCarousel">
                <button class="carousel-arrow carousel-prev" aria-label="Previous">‹</button>
                <div class="carousel-viewport">
                    <div class="carousel-track">
                        <?php foreach ($movies as $m): ?>
                            <div class="carousel-slide">
                                <?php 
                                    $posterUrl = '';
                                    if (!empty($m['poster'])) {
                                        $poster = htmlspecialchars($m['poster']);
                                        // Check if it's a full URL
                                        if (strpos($poster, 'http') === 0) {
                                            $posterUrl = $poster;
                                        } else {
                                            // It's a relative path
                                            $posterUrl = BASE_URL . '/public/' . $poster;
                                        }
                                    } else {
                                        // Fallback placeholder
                                        $posterUrl = 'https://placehold.co/300x420/1e1e2e/FFF?text=' . urlencode($m['title']);
                                    }
                                ?>
                                <img src="<?= $posterUrl ?>" alt="<?= htmlspecialchars($m['title']) ?>" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="carousel-arrow carousel-next" aria-label="Next">›</button>
                <div class="carousel-dots"></div>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 3rem 0; color: #9ca3af;">
                <p style="font-size: 1.2rem; margin-bottom: 0.5rem;">🎬</p>
                <p>No movies available at the moment. Check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="why-choose">
    <div class="cb-container">
        <h2 class="section-title">Why Choose <?= APP_NAME ?>?</h2>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">🎟️</div>
                <h3>Easy Booking</h3>
                <p>Book your tickets online in just a few clicks. Choose your seats, select showtimes, and pay securely.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🍿</div>
                <h3>Premium Experience</h3>
                <p>Enjoy state-of-the-art sound systems, comfortable seating, and fresh concessions for the ultimate movie experience.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h3>Flexible Payment</h3>
                <p>Multiple payment options available. Earn rewards points with every booking and enjoy exclusive member benefits.</p>
            </div>
        </div>
    
        <div class="mission-vision-grid" style="margin-top:2.25rem;">
            <div class="mission-card">
                <div class="mission-icon">🎯</div>
                <h3 class="section-title">Mission Statement</h3>
                <p class="mission-text">
                    To provide a secure and user-friendly system that allows customers to browse movies, view real-time availability, reserve seats, and make online payments while supporting cinema operators with efficient management and analytics tools.
                </p>
            </div>
            <div class="vision-card">
                <div class="vision-icon">🌟</div>
                <h3 class="section-title">Vision Statement</h3>
                <p class="vision-text">
                    To become the most seamless and engaging digital platform for moviegoers, offering fast, reliable, and enjoyable cinema booking experiences that redefine how audiences connect with entertainment.
                </p>
            </div>
        </div>
    </div>
</section>