<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies - screenWave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0f; color: #e5e7eb; }
        .hero-bg {
            background-image: linear-gradient(rgba(10, 10, 15, 0.8), rgba(10, 10, 15, 1)), url('https://placehold.co/1920x1080/0a0a0f/e5e7eb?text=CineBook+Movies');
            background-size: cover; background-position: center;
        }
        .movie-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .movie-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2); }
        .glassmorphism { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen">
        <section class="py-8 bg-gray-900/50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center gap-4 justify-center md:justify-start">
                    <span class="font-semibold mr-4">Filter by:</span>
                    <a href="<?= BASE_URL ?>/public/movies&status=now_showing" class="<?= $status !== 'coming' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300' ?> py-2 px-4 rounded-full text-sm hover:bg-indigo-700 transition">
                        Now Showing
                    </a>
                    
                    <a href="<?= BASE_URL ?>/public/movies&status=coming" class="<?= $status === 'coming' ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300' ?> py-2 px-4 rounded-full text-sm hover:bg-indigo-700 transition">
                        Coming Soon
                    </a>
                </div>
            </div>
        </section>

        <section id="movie-grid" class="py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                
                <?php if (empty($movies)): ?>
                    <div class="text-center py-20">
                        <h3 class="text-2xl text-gray-400">No movies found.</h3>
                        <p class="text-gray-500 mt-2">Check back later for updates!</p>
                    </div>
                <?php else: ?>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8">
                    
                    <?php foreach ($movies as $movie): ?>
                        <div class="movie-card bg-gray-900 rounded-xl overflow-hidden group h-full flex flex-col">
                            
                            <div class="relative overflow-hidden aspect-[2/3]">
                                <?php 
                                    $posterUrl = !empty($movie['poster']) ? $movie['poster'] : 'https://placehold.co/300x450/1f2937/ffffff?text=No+Image';
                                ?>
                                <img src="<?= htmlspecialchars($posterUrl) ?>" 
                                     alt="<?= htmlspecialchars($movie['title']) ?>" 
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                
                                <?php if(isset($movie['rating'])): ?>
                                <div class="absolute top-2 right-2 bg-black/70 backdrop-blur-sm text-yellow-400 text-xs font-bold px-2 py-1 rounded">
                                    ★ <?= htmlspecialchars($movie['rating']) ?>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="p-4 flex flex-col flex-grow">
                                <h3 class="font-bold text-lg text-white truncate mb-1" title="<?= htmlspecialchars($movie['title']) ?>">
                                    <?= htmlspecialchars($movie['title']) ?>
                                </h3>
                                
                                <p class="text-sm text-gray-400 line-clamp-2 mb-4 flex-grow">
                                    <?= htmlspecialchars($movie['description'] ?? 'No description available.') ?>
                                </p>

                                <?php if ($status === 'coming'): ?>
                                    <button class="w-full mt-auto bg-gray-700 text-white font-semibold py-2 rounded-lg cursor-not-allowed opacity-70">
                                        Coming Soon
                                    </button>
                                <?php else: ?>
                                    <a href="<?= BASE_URL ?>/public/browse/show/<?= $movie['id'] ?>" 
                                       class="block w-full mt-auto bg-indigo-600 text-white font-semibold py-2 rounded-lg hover:bg-indigo-700 transition duration-300 text-center">
                                        Book Now
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <div class="mt-12 flex justify-center space-x-4">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= BASE_URL ?>/public/movies&page=<?= $currentPage - 1 ?>&status=<?= $status ?>" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Previous</a>
                    <?php endif; ?>
                    
                    <?php if (count($movies) >= 12): ?> 
                        <a href="<?= BASE_URL ?>/public/movies&page=<?= $currentPage + 1 ?>&status=<?= $status ?>" class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Next</a>
                    <?php endif; ?>
                </div>

                <?php endif; ?>
            </div>
        </section>

    </main>

  

</body>
</html>