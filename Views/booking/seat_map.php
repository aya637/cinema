<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - <?= htmlspecialchars($show['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Seats - <?= htmlspecialchars($show['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/seat_map.css">
 
</head>
<body class="antialiased min-h-screen flex flex-col">
    <main class="pt-20 flex-grow">
        <section class="bg-gray-900/50 py-8 border-b border-gray-800">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-8 items-center md:items-start">
                    <div class="w-32 md:w-48 flex-shrink-0">
                        <?php $posterUrl = !empty($show['poster']) ? $show['poster'] : 'https://placehold.co/300x450/1e1e2e/FFF?text=No+Poster'; ?>
                        <img src="<?= htmlspecialchars($posterUrl) ?>" alt="<?= htmlspecialchars($show['title']) ?>" class="rounded-lg shadow-lg w-full object-cover">
                    </div>
                    <div class="flex-grow text-center md:text-left">
                        <h1 class="text-3xl md:text-5xl font-bold text-white leading-tight mb-2"><?= htmlspecialchars($show['title']) ?></h1>
                        <p class="text-indigo-400 font-semibold text-lg"><?= date('l, d M Y', strtotime($show['show_time'])) ?> • <?= date('h:i A', strtotime($show['show_time'])) ?></p>
                    </div>
                </div>
            </div>
        </section>

        <section id="seat-selection" class="py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-3 lg:gap-12">
                    <div class="lg:col-span-2">
                        <h2 class="text-2xl font-bold text-white mb-6">Select Your Seats</h2>
                        <div class="screen-visual">SCREEN</div>
                        <div id="loading" class="text-center py-10 text-gray-500 animate-pulse">Loading seat map...</div>
                        <div id="seat-map" class="seat-grid hidden"></div>
                    </div>

                    <div class="mt-12 lg:mt-0">
                        <div class="glassmorphism rounded-xl p-6 sticky top-24">
                            <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-4">Booking Summary</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="font-bold text-lg text-indigo-300"><?= htmlspecialchars($show['title']) ?></h3>
                                    <p class="text-sm text-gray-400">Price per Ticket: $<?= number_format($show['base_price'], 2) ?></p>
                                </div>
                                <div class="border-t border-gray-700 pt-4">
                                    <div class="flex justify-between text-gray-300 mb-2"><span>Seats</span><span id="ticket-count" class="font-semibold text-white">0</span></div>
                                    <div class="flex justify-between text-gray-300 text-sm"><span>Numbers</span><span id="selected-seats-list" class="text-right text-indigo-400 max-w-[150px] truncate">None</span></div>
                                </div>
                                <div class="mt-6 border-t border-gray-700 pt-4">
                                    <div class="flex justify-between items-center text-lg"><span class="font-semibold">Total Price</span><span id="total-price" class="font-bold text-2xl text-white">$0.00</span></div>
                                </div>
                                <button id="book-btn" onclick="bookTickets()" disabled class="w-full mt-6 btn-primary text-white font-bold py-3 px-8 rounded-full transition duration-300 transform active:scale-95 shadow-lg shadow-indigo-500/30">Confirm Booking</button>
                                <p id="error-msg" class="text-red-500 text-center text-sm mt-3 hidden"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        const showId = <?= json_encode($show['show_id']) ?>;
        const basePrice = <?= json_encode((float)$show['base_price']) ?>;
        
        let baseUrl = '<?= defined('BASE_URL') ? BASE_URL : '' ?>';
        if (!baseUrl) {
             const pathArray = window.location.pathname.split('/');
             baseUrl = window.location.origin + pathArray.slice(0, pathArray.indexOf('public')).join('/');
        }
        
        let selectedSeats = [];

        document.addEventListener('DOMContentLoaded', () => { fetchSeats(); });

        function fetchSeats() {
            const url = `${baseUrl}/public/index.php?url=booking/getSeatMap/${showId}`;
            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if(data.success) { renderSeats(data.seats); } else { showError(data.message); }
                })
                .catch(err => showError("Failed to load seats."));
        }

        function renderSeats(seats) {
            const container = document.getElementById('seat-map');
            document.getElementById('loading').classList.add('hidden');
            container.classList.remove('hidden');
            container.innerHTML = '';

            seats.forEach(seat => {
                const seatEl = document.createElement('div');
                let classes = 'seat ' + (seat.status === 'taken' ? 'taken' : 'available');
                seatEl.className = classes;
                seatEl.textContent = `${seat.row_label}${seat.seat_number}`;
                if (seat.status !== 'taken') { seatEl.onclick = () => toggleSeat(seat, seatEl); }
                container.appendChild(seatEl);
            });
        }

        function toggleSeat(seatData, element) {
            const label = `${seatData.row_label}${seatData.seat_number}`;
            const index = selectedSeats.findIndex(s => s.id === seatData.id);

            if (index > -1) {
                selectedSeats.splice(index, 1);
                element.classList.remove('selected');
            } else {
                selectedSeats.push({ id: seatData.id, label: label, price: basePrice });
                element.classList.add('selected');
            }
            updateSummary();
        }

        function updateSummary() {
            document.getElementById('ticket-count').textContent = selectedSeats.length;
            const total = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);
            document.getElementById('total-price').textContent = `$${total.toFixed(2)}`;
            
            const btn = document.getElementById('book-btn');
            const list = document.getElementById('selected-seats-list');
            
            if (selectedSeats.length > 0) {
                list.textContent = selectedSeats.map(s => s.label).join(', ');
                btn.disabled = false;
            } else {
                list.textContent = 'None';
                btn.disabled = true;
            }
        }

        function bookTickets() {
            if (selectedSeats.length === 0) return;
            const btn = document.getElementById('book-btn');
            btn.textContent = 'Processing...';
            btn.disabled = true;

            const seatIds = selectedSeats.map(s => s.id);
            const totalAmount = selectedSeats.reduce((sum, seat) => sum + seat.price, 0);

            fetch(`${baseUrl}/public/index.php?url=booking/createBooking`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ show_id: showId, seat_ids: seatIds, total: totalAmount })
            })
            .then(res => res.json())
            .then(data => {
                if (data.requires_auth) {
                    const loginUrl = data.login_url || `${baseUrl}/public/index.php?url=login`;
                    btn.textContent = 'Redirecting to sign in...';
                    window.location.href = loginUrl;
                    return;
                }

                if (data.success) {
                    // Redirect to concessions (no booking_id needed - using session)
                    window.location.href = `${baseUrl}/public/concessions`; 
                } else {
                    showError(data.message || 'Failed to store seat selection');
                    btn.textContent = 'Confirm Booking';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                showError('Network error.');
                btn.textContent = 'Confirm Booking';
                btn.disabled = false;
            });
        }

        function showError(msg) {
            const el = document.getElementById('error-msg');
            el.textContent = msg;
            el.classList.remove('hidden');
            setTimeout(() => el.classList.add('hidden'), 5000);
        }
    </script>
</body>
</html>