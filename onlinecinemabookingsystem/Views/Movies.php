<?php
// =========================
// File: header.php
// Common header (includes Tailwind + fonts). Use include 'header.php'; at the top of pages
// =========================
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'CineBook - Online Cinema Booking' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Small helper to keep both themes working when included by different pages */
        .hero-bg { background-size: cover; background-position: center; }
        .movie-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .movie-card:hover { transform: translateY(-8px); }
        .btn-primary { background-image: linear-gradient(to right, #4f46e5, #7c3aed); }
    </style>
</head>
<body class="antialiased">
<?php
// =========================
// End header.php
// =========================
?>


<?php
// =========================
// File: footer.php
// Common footer (use include 'footer.php'; at the bottom of pages)
// =========================
?>

<footer class="bg-gray-900 mt-16">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">Cine<span class="text-indigo-400">Book</span></h3>
                <p class="text-gray-400">Your ultimate destination for booking movie tickets online with ease and comfort.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-4">Quick Links</h4>
                <ul>
                    <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                    <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                    <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white">Careers</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-4">Legal</h4>
                <ul>
                    <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white">Terms of Service</a></li>
                    <li class="mb-2"><a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">Facebook</a>
                    <a href="#" class="text-gray-400 hover:text-white">X</a>
                    <a href="#" class="text-gray-400 hover:text-white">Instagram</a>
                </div>
            </div>
        </div>
        <div class="mt-8 border-t border-gray-800 pt-8 text-center text-gray-500">
            <p>&copy; <?= date('Y') ?> CineBook. All Rights Reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>

<?php
// =========================
// File: cinebook.php
// Converted from the dark Tailwind-based HTML you provided. This page uses header/footer includes
// Usage: put header.php, footer.php and this file in the same folder and visit cinebook.php
// =========================

$pageTitle = 'CineBook - Home';
include __DIR__ . '/header.php';
?>

<!-- Header (custom fixed header area) -->
<header class="fixed top-0 left-0 right-0 z-50 glassmorphism bg-opacity-10">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <div class="flex items-center">
                <a href="#" class="text-2xl font-bold tracking-wider">Cine<span class="text-indigo-400">Book</span></a>
            </div>
            <nav class="hidden md:flex items-center space-x-8">
                <a href="HomePage.php" class="text-gray-300 hover:text-white transition duration-300">Home Page</a>
                <a href="Movies.php" class="text-gray-300 hover:text-white transition duration-300">Movies</a>
                <a href="LoginPage.php" class="text-gray-300 hover:text-white transition duration-300">Login</a>
                <a href="RegistrationPage.php" class="text-gray-300 hover:text-white transition duration-300">Registration</a>
            </nav>
            <div class="flex items-center space-x-4">
                <button class="text-gray-300 hover:text-white transition duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center font-bold text-white">
                    <img src="https://placehold.co/40x40/7c3aed/ffffff?text=U" class="rounded-full" alt="User Avatar">
                </div>
            </div>
        </div>
    </div>
</header>

<main class="pt-20">
    <?php
    // featured and movie lists are arrays so you can later fetch from DB instead
    $featured = [
        'title' => 'Galactic Echoes',
        'description' => 'In a future where humanity has scattered across the stars, an ancient signal pulls a lone scavenger towards a destiny that will change the galaxy forever.',
        'banner' => 'https://placehold.co/1920x1080/0a0a0f/e5e7eb?text=Featured+Movie+Banner'
    ];

    $movies = [
        ['title' => 'Top Gun', 'genre' => 'Action, Sci-Fi', 'poster' => 'images/Top_Gun_Maverick_Poster.jpg', 'link' => 'Booking.php'],
        ['title' => 'Cosmic Drift', 'genre' => 'Adventure, Sci-Fi', 'poster' => 'https://placehold.co/300x450/ff4500/ffffff?text=Cosmic+Drift'],
        ['title' => 'The Last Laugh', 'genre' => 'Comedy', 'poster' => 'https://placehold.co/300x450/00ced1/ffffff?text=The+Last+Laugh'],
        ['title' => 'Shadow Creek', 'genre' => 'Horror, Thriller', 'poster' => 'https://placehold.co/300x450/8b0000/ffffff?text=Shadow+Creek'],
        ['title' => "Ocean's Heart", 'genre' => 'Romance, Drama', 'poster' => 'https://placehold.co/300x450/ffd700/000000?text=Ocean%27s+Heart']
    ];
    ?>

    <!-- Hero Section -->
    <section class="hero-bg pt-20" style="background-image: linear-gradient(rgba(10,10,15,0.8), rgba(10,10,15,1)), url('<?= $featured['banner'] ?>')">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-[60vh] md:h-[70vh] flex flex-col justify-center items-start text-left">
            <div class="max-w-xl">
                <span class="text-sm font-semibold text-indigo-300 uppercase tracking-widest">Featured Film</span>
                <h1 class="text-4xl md:text-6xl font-bold text-white mt-4 mb-6 leading-tight tracking-tight"><?= htmlspecialchars($featured['title']) ?></h1>
                <p class="text-gray-300 text-lg mb-8 max-w-lg"><?= htmlspecialchars($featured['description']) ?></p>
                <a href="#" class="btn-primary text-white font-bold py-3 px-8 rounded-full transition duration-300 transform hover:scale-105 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                    </svg>
                    Watch Trailer
                </a>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="py-8 bg-gray-900/50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-center gap-4">
                <span class="font-semibold mr-4">Filter by:</span>
                <div class="relative">
                    <select class="appearance-none bg-gray-800 border border-gray-700 text-white py-2 px-4 pr-8 rounded-full focus:outline-none focus:bg-gray-700 focus:border-indigo-500">
                        <option>Genre</option>
                        <option>Action</option>
                        <option>Comedy</option>
                        <option>Sci-Fi</option>
                        <option>Horror</option>
                    </select>
                </div>
                <div class="relative">
                     <select class="appearance-none bg-gray-800 border border-gray-700 text-white py-2 px-4 pr-8 rounded-full focus:outline-none focus:bg-gray-700 focus:border-indigo-500">
                        <option>Language</option>
                        <option>English</option>
                        <option>Spanish</option>
                        <option>Arabic</option>
                    </select>
                </div>
                <div class="relative">
                     <select class="appearance-none bg-gray-800 border border-gray-700 text-white py-2 px-4 pr-8 rounded-full focus:outline-none focus:bg-gray-700 focus:border-indigo-500">
                        <option>Format</option>
                        <option>2D</option>
                        <option>3D</option>
                        <option>IMAX</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Now Showing -->
    <section id="now-showing" class="py-16 sm:py-24">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white mb-8">Now Showing</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8">
                <?php foreach ($movies as $m): ?>
                <div class="movie-card bg-gray-900 rounded-xl overflow-hidden group">
                    <img src="<?= htmlspecialchars($m['poster']) ?>" alt="<?= htmlspecialchars($m['title']) ?> Poster" class="w-full h-auto transform group-hover:scale-105 transition-transform duration-300">
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-white truncate"><?= htmlspecialchars($m['title']) ?></h3>
                        <p class="text-sm text-gray-400"><?= htmlspecialchars($m['genre']) ?></p>
                        <a href="<?= isset($m['link']) ? htmlspecialchars($m['link']) : '#' ?>" class="block w-full mt-4 bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition duration-300 text-center">Book Now</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Coming Soon -->
    <section id="coming-soon" class="py-16 sm:py-24 bg-gray-900/50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-white mb-8">Coming Soon</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8">
                <div class="movie-card bg-gray-900 rounded-xl overflow-hidden group opacity-80">
                    <img src="https://placehold.co/300x450/4b0082/ffffff?text=Cyber+Dawn" alt="Cyber Dawn" class="w-full h-auto">
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-white truncate">Cyber Dawn</h3>
                        <p class="text-sm text-gray-400">Arriving Dec 2025</p>
                        <button class="w-full mt-4 bg-gray-700 text-white font-semibold py-2 rounded-lg cursor-not-allowed">Notify Me</button>
                    </div>
                </div>
                <div class="movie-card bg-gray-900 rounded-xl overflow-hidden group opacity-80">
                    <img src="https://placehold.co/300x450/228b22/ffffff?text=Jungle+Rumble" alt="Jungle Rumble" class="w-full h-auto">
                    <div class="p-4">
                        <h3 class="font-bold text-lg text-white truncate">Jungle Rumble 3</h3>
                        <p class="text-sm text-gray-400">Arriving Jan 2026</p>
                        <button class="w-full mt-4 bg-gray-700 text-white font-semibold py-2 rounded-lg cursor-not-allowed">Notify Me</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php include __DIR__ . '/footer.php'; ?>

<?php
// =========================
// File: movies_theme.php
// Converted the second (light purple) CSS + layout into a PHP page. It's standalone and demonstrates the alternate theme.
// =========================

$pageTitle = 'CineBook - Movies (Light Theme)';
include __DIR__ . '/header.php';
?>

<style>
/* Override header default font for the second theme */
body { background-color: #f5f3ff; color: #2d0a57; font-family: 'Poppins', sans-serif; }
header.topbar { background-color: #2d0a57; padding: 10px 40px; }
.logo { color: #d4b3ff; font-size: 22px; font-weight: bold; }
.movie-card-alt { background-color: #fff; color: #2d0a57; padding: 12px; border-radius: 10px; width: 200px; }
.movie-card-alt img { width: 100%; border-radius: 8px; }
button.btn-alt { background-color: #4b0082; color: #fff; padding: 8px 14px; border-radius: 6px; }
button.btn-alt:hover { background-color: #6a0dad; }
</style>

<header class="topbar fixed w-full z-40">
    <div class="container mx-auto flex items-center justify-between">
        <div class="logo">CineBook</div>
        <nav class="hidden md:flex items-center gap-6">
            <a href="#" class="text-white">Home</a>
            <a href="#" class="text-white">Movies</a>
            <a href="#" class="text-white">Contact</a>
        </nav>
    </div>
</header>

<main class="pt-24">
    <section class="movies-section">
        <h2>Now Showing</h2>
        <div class="movie-container container mx-auto px-4 mt-6">
            <?php foreach ($movies as $m): ?>
            <div class="movie-card-alt">
                <img src="<?= htmlspecialchars($m['poster']) ?>" alt="<?= htmlspecialchars($m['title']) ?>">
                <h3><?= htmlspecialchars($m['title']) ?></h3>
                <p><?= htmlspecialchars($m['genre']) ?></p>
                <button class="btn-alt mt-2">Book Now</button>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

</main>

<?php include __DIR__ . '/footer.php'; ?>

<?php
// =========================
// Notes:
// - These are static PHP templates. To make them dynamic, replace the $movies array with a DB query (PDO/Mysqli).
// - Keep header.php and footer.php to share common markup.
// - If you want separate header styles per theme, create header_dark.php / header_light.php and include accordingly.
// =========================
?>