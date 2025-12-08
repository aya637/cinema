<?php
// app/views/homepage/index.php
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
                <a href="#now-showing" class="btn btn-primary">Book Now</a>
                <a href="#" class="btn btn-outline">View Showtimes</a>
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

<?php if (!empty($posters) && is_array($posters)): ?>
    <script>
    // Simple carousel script — initialize after DOM is ready and add safety checks
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
        
        <?php if (!empty($posters) && is_array($posters)): ?>
            <div class="carousel" id="nowShowingCarousel">
                <button class="carousel-arrow carousel-prev" aria-label="Previous">‹</button>
                <div class="carousel-viewport">
                    <div class="carousel-track">
                        <?php foreach ($posters as $p): ?>
                            <div class="carousel-slide">
                                <?php $posterImg = htmlspecialchars($p['image_path'] ?? $p['image'] ?? ''); ?>
                                <button class="poster-open" type="button"
                                    data-title="<?= htmlspecialchars($p['title'] ?? '') ?>"
                                    data-desc="<?= htmlspecialchars($p['description'] ?? '') ?>"
                                    data-img="<?= BASE_URL ?>/images/<?= $posterImg ?>">
                                    <img src="<?= BASE_URL ?>/images/<?= $posterImg ?>" alt="<?= htmlspecialchars($p['title'] ?? 'Poster') ?>" />
                                </button>
                                <!-- carousel CTA removed per request -->
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button class="carousel-arrow carousel-next" aria-label="Next">›</button>
                <div class="carousel-dots"></div>
            </div>
        <?php elseif (!empty($movies) && is_array($movies)): ?>
            <div class="movie-grid">
                <?php foreach ($movies as $movie): ?>
                    <article class="movie-card">
                        <div class="movie-thumb emoji">
                                <?php if (!empty($movie['image_path']) || !empty($movie['image'])): ?>
                                <?php $img = htmlspecialchars($movie['image_path'] ?? $movie['image']); ?>
                                <button class="poster-open poster-open-small" type="button"
                                    data-title="<?= htmlspecialchars($movie['title'] ?? '') ?>"
                                    data-desc="<?= htmlspecialchars($movie['description'] ?? '') ?>"
                                    data-img="<?= BASE_URL ?>/images/<?= $img ?>">
                                    <img src="<?= BASE_URL ?>/images/<?= $img ?>" alt="<?= htmlspecialchars($movie['title'] ?? 'Poster') ?>">
                                </button>
                            <?php else: ?>
                                <?= htmlspecialchars($movie['emoji'] ?? '🎬') ?>
                            <?php endif; ?>
                        </div>
                        <div class="movie-body">
                            <h3 class="movie-title"><?= htmlspecialchars($movie['title']) ?></h3>
                            <p class="movie-meta"><?= htmlspecialchars($movie['genre']) ?> · <?= htmlspecialchars($movie['duration']) ?></p>
                            <div class="movie-footer">
                                <span class="movie-rating">⭐ <?= number_format($movie['rating'], 1) ?>/10</span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
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

<!-- Poster Modal -->
<div id="posterModal" class="poster-modal" aria-hidden="true">
    <div class="poster-modal-overlay" data-close></div>
    <div class="poster-modal-panel" role="dialog" aria-modal="true" aria-labelledby="posterModalTitle">
        <button class="poster-modal-close" aria-label="Close" data-close>×</button>
        <div class="poster-modal-body">
            <img src="" alt="Poster" class="poster-modal-img">
            <div class="poster-modal-info">
                <h3 id="posterModalTitle" class="poster-modal-title"></h3>
                <p class="poster-modal-desc"></p>
            </div>
        </div>
    </div>
</div>

<script>
// Modal behavior for poster previews
(function(){
    function qs(selector, root=document){ return root.querySelector(selector); }
    const modal = qs('#posterModal');
    if (!modal) return;
    const imgEl = qs('.poster-modal-img', modal);
    const titleEl = qs('.poster-modal-title', modal);
    const descEl = qs('.poster-modal-desc', modal);
    const closeEls = modal.querySelectorAll('[data-close]');

    function openModal(data){
        imgEl.src = data.img || '';
        imgEl.alt = data.title || 'Poster';
        titleEl.textContent = data.title || '';
        descEl.textContent = data.desc || '';
        modal.classList.add('open');
        modal.setAttribute('aria-hidden','false');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(){
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden','true');
        imgEl.src = '';
        document.body.style.overflow = '';
    }

    closeEls.forEach(el => el.addEventListener('click', closeModal));
    modal.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeModal(); });

    // Attach click handlers
    const triggers = Array.from(document.querySelectorAll('.poster-open'));
    triggers.forEach(btn => {
        btn.addEventListener('click', function(){
            const data = {
                title: btn.getAttribute('data-title') || '',
                desc: btn.getAttribute('data-desc') || '',
                img: btn.getAttribute('data-img') || ''
            };
            openModal(data);
        });
    });

    // Close when clicking overlay
    qs('.poster-modal-overlay', modal).addEventListener('click', closeModal);
})();
</script>