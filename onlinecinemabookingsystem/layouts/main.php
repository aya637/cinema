<?php
// layouts/main.php
// COMPLETE VERSION: Navigation + AI Chat Widget
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' : '' ?><?= APP_NAME ?></title>
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="antialiased">
    <div class="page-root relative min-h-screen flex flex-col">
        
        <header class="cb-header bg-black/90 backdrop-blur-md border-b border-white/10 sticky top-0 z-40">
            <div class="cb-container cb-header-inner flex justify-between items-center py-4 px-6 max-w-7xl mx-auto">
                <a href="<?= BASE_URL ?>/public/" class="cb-logo flex items-center gap-2 text-white font-bold text-xl no-underline">
                    <img src="<?= BASE_URL ?>/public/uploads/images/websitelogo.jpg" alt="<?= APP_NAME ?> logo" class="cb-logo-icon h-8 w-8 rounded-full object-cover">
                    <span class="cb-logo-text"><span class="text-indigo-500">Screen</span>wave</span>
                </a>

                <nav class="cb-nav hidden md:flex gap-6">
                    <a href="<?= BASE_URL ?>/public/" class="cb-nav-link text-gray-300 hover:text-white transition">Home</a>
                    <a href="<?= BASE_URL ?>/public/movies" class="cb-nav-link text-gray-300 hover:text-white transition">Movies</a>
                    <a href="#" class="cb-nav-link text-gray-300 hover:text-white transition">Contact</a>
                </nav>

                <div class="cb-auth-buttons flex items-center gap-4">
                    <?php if (!empty($_SESSION['user_id'])): ?>
                        <a href="<?= BASE_URL ?>/public/index.php?url=profile" class="text-sm font-medium text-gray-300 hover:text-white">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'Profile') ?>
                        </a>
                        <a href="<?= BASE_URL ?>/public/index.php?url=logout" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>/public/index.php?url=login" class="text-sm font-medium text-gray-300 hover:text-white">Sign In</a>
                        <a href="<?= BASE_URL ?>/public/index.php?url=signup" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            Sign Up
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="flex-grow">
            <?= $content ?? '' ?>
        </main>

        <footer class="cb-footer bg-black border-t border-white/10 pt-12 pb-8">
            <div class="cb-container cb-footer-inner max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="cb-footer-section col-span-1 md:col-span-1">
                    <div class="cb-logo footer-logo flex items-center gap-2 mb-4 text-white font-bold">
                        <img src="<?= BASE_URL ?>/public/uploads/images/websitelogo.jpg" alt="<?= APP_NAME ?> logo" class="h-8 w-8 rounded-full">
                        <span><span class="text-indigo-500">Screen</span>wave</span>
                    </div>
                    <p class="cb-footer-text text-gray-400 text-sm leading-relaxed">
                        Your premier destination for the latest movies and unforgettable cinema experiences.
                    </p>
                </div>
                
                <div class="col-span-1">
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="<?= BASE_URL ?>/public/index.php?url=movies" class="hover:text-indigo-400">Now Showing</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Coming Soon</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Gift Cards</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Membership</a></li>
                    </ul>
                </div>

                <div class="col-span-1">
                    <h4 class="text-white font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-indigo-400">Help Center</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Contact Us</a></li>
                        <li><a href="#" class="hover:text-indigo-400">Refund Policy</a></li>
                    </ul>
                </div>

                <div class="col-span-1">
                    <h4 class="text-white font-semibold mb-4">Connect</h4>
                    <div class="cb-social-row flex gap-4">
                        <a href="#" class="opacity-70 hover:opacity-100 transition"><img src="<?= BASE_URL ?>/images/facebook.png" alt="FB" class="h-6 w-6"></a>
                        <a href="#" class="opacity-70 hover:opacity-100 transition"><img src="<?= BASE_URL ?>/images/instgram.png" alt="IG" class="h-6 w-6"></a>
                        <a href="#" class="opacity-70 hover:opacity-100 transition"><img src="<?= BASE_URL ?>/images/tiktok.png" alt="TT" class="h-6 w-6"></a>
                        <a href="#" class="opacity-70 hover:opacity-100 transition"><img src="<?= BASE_URL ?>/images/youtube.png" alt="YT" class="h-6 w-6"></a>
                    </div>
                </div>
            </div>
            
            <div class="cb-footer-bottom border-t border-white/10 pt-8 text-center">
                <p class="text-gray-500 text-sm">© 2024 Screenwave. All rights reserved.</p>
            </div>
        </footer>

        <div id="ai-widget" class="fixed bottom-6 right-6 z-50 flex flex-col items-end font-sans">
            
            <div id="chat-window" class="hidden w-80 mb-4 bg-gray-900 border border-gray-700 rounded-2xl shadow-2xl overflow-hidden flex flex-col transition-all duration-300 transform origin-bottom-right scale-90 opacity-0">
                <div class="bg-indigo-600 p-4 flex justify-between items-center">
                    <h3 class="font-bold text-white flex items-center gap-2">
                        <span>🤖</span> CineBot
                    </h3>
                    <button onclick="toggleChat()" class="text-white hover:text-gray-200 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                <div id="chat-messages" class="h-80 overflow-y-auto p-4 space-y-3 bg-gray-900/95 scroll-smooth">
                    <div class="flex items-start">
                        <div class="bg-gray-800 text-gray-200 rounded-lg rounded-tl-none p-3 text-sm max-w-[85%] border border-gray-700">
                            Hi! I'm CineBot. Tell me what kind of movie you're in the mood for, and I'll check our schedule for you!
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-gray-800 border-t border-gray-700">
                    <form id="chat-form" onsubmit="sendMessage(event)" class="flex gap-2">
                        <input type="text" id="user-input" placeholder="e.g. funny action movies..." 
                               class="flex-1 bg-gray-900 text-white text-sm rounded-full px-4 py-2 border border-gray-600 focus:outline-none focus:border-indigo-500 placeholder-gray-500">
                        <button type="submit" class="bg-indigo-600 text-white rounded-full p-2 hover:bg-indigo-700 transition flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform rotate-90" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <button onclick="toggleChat()" class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg transition transform hover:scale-105 group relative">
                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </button>
        </div>

        <script>
            // Chat Widget Logic
            const chatWindow = document.getElementById('chat-window');
            const messagesContainer = document.getElementById('chat-messages');

            function toggleChat() {
                chatWindow.classList.toggle('hidden');
                // Small delay to allow 'hidden' removal to register before animating opacity
                if (!chatWindow.classList.contains('hidden')) {
                    setTimeout(() => {
                        chatWindow.classList.remove('opacity-0', 'scale-90');
                        document.getElementById('user-input').focus();
                    }, 10);
                } else {
                    chatWindow.classList.add('opacity-0', 'scale-90');
                }
            }

            async function sendMessage(e) {
                e.preventDefault();
                const input = document.getElementById('user-input');
                const message = input.value.trim();
                if (!message) return;

                // 1. Add User Message
                appendMessage(message, 'user');
                input.value = '';

                // 2. Add Loading Indicator
                const loadingId = appendLoading();

                try {
                    // 3. Send to PHP Backend
                    const response = await fetch('<?= BASE_URL ?>/public/ai/recommend', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ prompt: message })
                    });

                    const data = await response.json();
                    
                    // Remove loading and show response
                    document.getElementById(loadingId).remove();
                    appendMessage(data.message, 'bot');

                } catch (error) {
                    document.getElementById(loadingId).remove();
                    appendMessage("Sorry, I'm having trouble connecting to the movie database right now.", 'bot');
                    console.error(error);
                }
            }

            function appendMessage(text, sender) {
                const div = document.createElement('div');
                div.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'}`;
                
                const bubble = document.createElement('div');
                bubble.className = sender === 'user' 
                    ? 'bg-indigo-600 text-white rounded-lg rounded-tr-none p-3 text-sm max-w-[85%]' 
                    : 'bg-gray-800 text-gray-200 rounded-lg rounded-tl-none p-3 text-sm max-w-[85%] border border-gray-700 leading-relaxed';
                
                // Allow simple HTML like <b> or <br>
                bubble.innerHTML = text;
                div.appendChild(bubble);
                messagesContainer.appendChild(div);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            function appendLoading() {
                const id = 'loading-' + Date.now();
                const div = document.createElement('div');
                div.id = id;
                div.className = 'flex justify-start';
                div.innerHTML = `
                    <div class="bg-gray-800 rounded-lg rounded-tl-none p-4 border border-gray-700">
                        <div class="flex space-x-2">
                            <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce delay-75"></div>
                            <div class="w-2 h-2 bg-gray-500 rounded-full animate-bounce delay-150"></div>
                        </div>
                    </div>`;
                messagesContainer.appendChild(div);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                return id;
            }
        </script>
        </div>
</body>
</html>