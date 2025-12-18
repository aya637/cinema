<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Snacks & Drinks - screenWave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0f; color: #e5e7eb; }
        .glassmorphism { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .btn-primary { background-image: linear-gradient(to right, #4f46e5, #7c3aed); }
        .btn-primary:hover:not(:disabled) { background-image: linear-gradient(to right, #4338ca, #6d28d9); }
        .movie-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .movie-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2); }
        .snack-image { filter: brightness(0.9); transition: filter 0.3s ease; }
        .movie-card:hover .snack-image { filter: brightness(1); }
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.85); z-index: 1000; align-items: center; justify-content: center; }
        .modal-overlay.open { display: flex; }
        .snack-modal { background: #1f2937; border-radius: 16px; max-width: 700px; width: 90%; max-height: 90vh; overflow-y: auto; padding: 2rem; position: relative; border: 1px solid rgba(255, 255, 255, 0.1); }
        .modal-close { position: absolute; top: 1rem; right: 1rem; background: transparent; border: none; color: white; font-size: 2rem; cursor: pointer; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 50%; transition: background 0.2s; }
        .modal-close:hover { background: rgba(255, 255, 255, 0.1); }
        .option-group { margin-bottom: 1.5rem; }
        .option-label { display: block; color: #cbd5f5; margin-bottom: 0.75rem; font-weight: 600; }
        .option-select { width: 100%; padding: 0.75rem; background: #374151; border: 1px solid #4b5563; border-radius: 8px; color: white; }
        .option-select:focus { outline: none; border-color: #6366f1; }
        .qty-wrapper { display: flex; align-items: center; gap: 1rem; }
        .qty-btn-m { background: #4f46e5; border: none; color: white; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.2s; }
        .qty-btn-m:hover { background: #4338ca; }
        .qty-val-m { color: white; font-weight: 600; min-width: 30px; text-align: center; }
        .add-btn { background: linear-gradient(to right, #4f46e5, #7c3aed); color: white; border: none; padding: 0.875rem 1.75rem; border-radius: 8px; font-weight: 600; cursor: pointer; width: 100%; transition: all 0.3s; }
        .add-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4); }
        .cart-item { background: rgba(55, 65, 81, 0.5); border-radius: 8px; padding: 0.75rem; margin-bottom: 0.5rem; }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen">

    <button type="button" onclick="history.back()" class="fixed top-4 left-4 z-40 bg-gray-900/80 border border-white/10 text-white px-3 py-2 rounded-full text-sm font-semibold shadow-lg hover:bg-gray-800 transition">
        ←
    </button>

    <main class="flex-grow pt-20">
        <section class="py-8 bg-gray-900/50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center gap-4 justify-center md:justify-start">
                    <span class="font-semibold mr-4">Filter by:</span>
                    <button type="button" class="filter-btn px-4 py-2 rounded-full text-sm <?= true ? 'bg-indigo-600 text-white' : 'bg-gray-800 text-gray-300' ?> hover:bg-indigo-700 transition" onclick="filterMenu('all', this)">
                        All Items
                    </button>
                    <button type="button" class="filter-btn px-4 py-2 rounded-full text-sm bg-gray-800 text-gray-300 hover:bg-gray-700 transition" onclick="filterMenu('Sandwiches', this)">
                        Sandwiches
                    </button>
                    <button type="button" class="filter-btn px-4 py-2 rounded-full text-sm bg-gray-800 text-gray-300 hover:bg-gray-700 transition" onclick="filterMenu('Sides', this)">
                        Sides & Drinks
                    </button>
                    <button type="button" class="filter-btn px-4 py-2 rounded-full text-sm bg-gray-800 text-gray-300 hover:bg-gray-700 transition" onclick="filterMenu('Sweets', this)">
                        Sweets
                    </button>
                </div>
            </div>
        </section>

        <section id="snacks-grid" class="py-12">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                    
                    <div class="lg:col-span-2">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 md:gap-8">
                            <?php foreach($items as $item): ?>
                                <div class="item-card movie-card bg-gray-900 rounded-xl overflow-hidden group h-full flex flex-col cursor-pointer" data-category="<?= htmlspecialchars($item['category']) ?>" onclick='openModal(<?= json_encode($item) ?>)'>
                                    
                                    <div class="relative overflow-hidden aspect-[2/3]">
                                        <?php 
                                            $imageUrl = !empty($item['image']) ? $item['image'] : 'https://placehold.co/300x450/1f2937/ffffff?text=No+Image';
                                        ?>
                                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                                             alt="<?= htmlspecialchars($item['name']) ?>" 
                                             class="snack-image w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300">
                                        
                                    </div>

                                    <div class="p-4 flex flex-col flex-grow">
                                        <h3 class="font-bold text-base text-white truncate mb-2" title="<?= htmlspecialchars($item['name']) ?>">
                                            <?= htmlspecialchars($item['name']) ?>
                                        </h3>
                                        
                                        <p class="text-xs text-gray-400 line-clamp-2 mb-3 flex-grow leading-relaxed">
                                            <?= htmlspecialchars($item['description'] ?? '') ?>
                                        </p>

                                        <div class="mt-auto pt-3 border-t border-gray-800">
                                            <div class="flex items-center justify-between">
                                                <div class="text-indigo-400 font-bold text-lg">
                                                    $<?= number_format($item['base_price'], 2) ?>
                                                </div>
                                                <div class="text-xs text-gray-500 uppercase tracking-wide">
                                                    <?= htmlspecialchars($item['category']) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-12 lg:mt-0">
                        <div class="glassmorphism rounded-xl p-5 sticky top-24">
                            <h2 class="text-xl font-bold text-white mb-4 border-b border-gray-700 pb-3">Order Summary</h2>
                            
                            <div class="mb-3 pb-3 border-b border-gray-700">
                                <h4 class="font-semibold text-base text-indigo-300 mb-1">
                                    <?= htmlspecialchars($cart['movie_title']) ?>
                                </h4>
                                <div class="text-xs text-gray-400">
                                    Seats: <span class="text-indigo-400"><?= htmlspecialchars($cart['seat_labels']) ?></span>
                                </div>
                            </div>

                            <div id="cart-items-container" class="max-h-48 overflow-y-auto mb-3 space-y-1.5">
                                <p class="text-gray-500 text-xs text-center py-4">Your cart is empty</p>
                            </div>
                            
                            <div class="space-y-1.5 border-t border-gray-700 pt-3 text-sm">
                                <div class="flex justify-between text-gray-300">
                                    <span>Ticket</span>
                                    <span>$<?= number_format($cart['ticket_price'], 2) ?></span>
                                </div>
                                <div class="flex justify-between text-gray-300">
                                    <span>Snacks</span>
                                    <span class="text-indigo-400" id="snack-total-display">$0.00</span>
                                </div>
                                <div class="flex justify-between items-center pt-2 border-t border-gray-700 mt-2">
                                    <span class="font-bold text-base text-white">Total</span>
                                    <span class="font-bold text-lg text-indigo-400" id="grand-total-display">$<?= number_format($cart['ticket_price'], 2) ?></span>
                                </div>
                            </div>
                            <button type="button" onclick="proceed()" class="w-full mt-4 btn-primary text-white font-bold py-2.5 px-6 rounded-full transition duration-300 text-sm">Proceed to Payment</button>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>

    <!-- Modal -->
    <div class="modal-overlay" id="productModal" onclick="if(event.target === this) closeModal()">
        <div class="snack-modal" onclick="event.stopPropagation()">
            <button type="button" class="modal-close" onclick="closeModal()">&times;</button>
            
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <img id="modalImg" class="w-full rounded-lg object-cover" style="aspect-ratio: 1; max-height: 400px;" src="" alt="Product">
                </div>
                
                <div class="flex flex-col">
                    <h2 id="modalTitle" class="text-2xl font-bold text-white mb-2">Product Name</h2>
                    <p id="modalDesc" class="text-gray-400 mb-4 text-sm">Description</p>
                    
                    <div id="modalOptionsContainer" class="mb-4 flex-grow"></div>

                    <div class="mt-auto space-y-4">
                        <div class="flex items-center justify-between bg-gray-800 rounded-lg p-3">
                            <span class="text-gray-300 font-semibold">Quantity</span>
                            <div class="qty-wrapper">
                                <button type="button" class="qty-btn-m" onclick="updateModalQty(-1)">−</button>
                                <span class="qty-val-m" id="modalQty">1</span>
                                <button type="button" class="qty-btn-m" onclick="updateModalQty(1)">+</button>
                            </div>
                        </div>
                        <div class="text-right mb-2">
                            <div class="text-gray-400 text-sm">Total Price</div>
                            <div class="text-2xl font-bold text-white" id="modalPrice">$0.00</div>
                        </div>
                        <button type="button" class="add-btn" onclick="addToCart()">Add to Order</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let ticketPrice = <?= $cart['ticket_price'] ?? 0 ?>;
        let currentItem = null;
        let currentPrice = 0;
        let currentQty = 1;
        let cart = []; 

        function openModal(item) {
            currentItem = item;
            currentQty = 1;
            document.getElementById('modalQty').innerText = 1;
            document.getElementById('modalTitle').innerText = item.name;
            document.getElementById('modalDesc').innerText = item.description || 'Premium quality item.';
            document.getElementById('modalImg').src = item.image || 'https://placehold.co/400x400/1f2937/ffffff?text=No+Image';
            
            const container = document.getElementById('modalOptionsContainer');
            container.innerHTML = '';
            
            if (item.variants && item.variants.trim()) {
                const groups = item.variants.split(';');
                groups.forEach(group => {
                    if(!group.trim()) return;
                    const [groupName, optionsStr] = group.split('|');
                    if(!groupName || !optionsStr) return;
                    const options = optionsStr.split(',');
                    
                    const div = document.createElement('div');
                    div.className = 'option-group';
                    const label = document.createElement('label');
                    label.className = 'option-label';
                    label.innerText = groupName;
                    
                    const select = document.createElement('select');
                    select.className = 'option-select';
                    select.onchange = calculatePrice;
                    
                    options.forEach(opt => {
                        if(!opt.trim()) return;
                        const [optName, priceMod] = opt.split(':');
                        const mod = parseFloat(priceMod) || 0;
                        const option = document.createElement('option');
                        option.value = mod;
                        option.text = `${optName} ${mod > 0 ? '(+$'+mod.toFixed(2)+')' : ''}`;
                        option.dataset.name = optName;
                        select.appendChild(option);
                    });
                    
                    div.appendChild(label);
                    div.appendChild(select);
                    container.appendChild(div);
                });
            } else {
                container.innerHTML = '<p class="text-gray-400 text-sm">No customization options available.</p>';
            }
            
            calculatePrice();
            document.getElementById('productModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() { 
            document.getElementById('productModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        function updateModalQty(change) {
            let newQty = currentQty + change;
            if (newQty < 1) newQty = 1;
            if (newQty > 99) newQty = 99;
            currentQty = newQty;
            document.getElementById('modalQty').innerText = currentQty;
            calculatePrice();
        }

        function calculatePrice() {
            if (!currentItem) return;
            let price = parseFloat(currentItem.base_price) || 0;
            const selects = document.querySelectorAll('.option-select');
            selects.forEach(sel => { 
                price += parseFloat(sel.value) || 0; 
            });
            currentPrice = price;
            let total = price * currentQty;
            document.getElementById('modalPrice').innerText = '$' + total.toFixed(2);
        }

        function addToCart() {
            if (!currentItem) return;
            const selects = document.querySelectorAll('.option-select');
            let details = [];
            selects.forEach(sel => {
                const optName = sel.options[sel.selectedIndex]?.dataset.name;
                if (optName) details.push(optName);
            });
            let detailsStr = details.length > 0 ? details.join(', ') : 'Standard';
            
            // Check if same item with same details already exists
            const existingIndex = cart.findIndex(item => 
                item.name === currentItem.name && item.details === detailsStr
            );
            
            if (existingIndex > -1) {
                // Update quantity if item exists
                cart[existingIndex].qty += currentQty;
            } else {
                // Add new item
                cart.push({
                    name: currentItem.name,
                    details: detailsStr,
                    unitPrice: currentPrice,
                    qty: currentQty
                });
            }
            renderCart();
            closeModal();
        }

        function updateCartItem(index, change) {
            cart[index].qty += change;
            if (cart[index].qty < 1) {
                cart[index].qty = 1;
            }
            if (cart[index].qty > 99) {
                cart[index].qty = 99;
            }
            renderCart();
        }

        function removeCartItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const container = document.getElementById('cart-items-container');
            if (cart.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-xs text-center py-4">Your cart is empty</p>';
            } else {
                container.innerHTML = '';
                let snackTotal = 0;
                cart.forEach((item, index) => {
                    let itemTotal = item.unitPrice * item.qty;
                    snackTotal += itemTotal;
                    const div = document.createElement('div');
                    div.className = 'cart-item';
                    div.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="text-white font-semibold text-xs mb-1">${item.name}</div>
                                <div class="text-gray-400 text-xs mb-2">${item.details}</div>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="updateCartItem(${index}, -1)" class="w-5 h-5 bg-gray-700 hover:bg-gray-600 rounded text-xs font-bold flex items-center justify-center">−</button>
                                    <span class="text-white text-xs font-semibold min-w-[20px] text-center">${item.qty}</span>
                                    <button type="button" onclick="updateCartItem(${index}, 1)" class="w-5 h-5 bg-gray-700 hover:bg-gray-600 rounded text-xs font-bold flex items-center justify-center">+</button>
                                    <button type="button" onclick="removeCartItem(${index})" class="ml-2 text-red-400 hover:text-red-300 text-xs">Remove</button>
                                </div>
                            </div>
                            <div class="text-white font-bold ml-2 text-xs">$${itemTotal.toFixed(2)}</div>
                        </div>
                    `;
                    container.appendChild(div);
                });
                
                let grandTotal = ticketPrice + snackTotal;
                document.getElementById('snack-total-display').textContent = '$' + snackTotal.toFixed(2);
                document.getElementById('grand-total-display').textContent = '$' + grandTotal.toFixed(2);
            }
        }

        function proceed() {
            let total = parseFloat(document.getElementById('grand-total-display').textContent.replace('$', ''));
            let snackTotal = parseFloat(document.getElementById('snack-total-display').textContent.replace('$', ''));
            // Store cart items in sessionStorage to pass to payment page
            sessionStorage.setItem('snackCart', JSON.stringify(cart));
            window.location.href = `index.php?url=payment/form&grand_total=${total}&snack_total=${snackTotal}`;
        }

        function filterMenu(cat, btn) {
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('bg-indigo-600', 'text-white');
                b.classList.add('bg-gray-800', 'text-gray-300');
            });
            btn.classList.remove('bg-gray-800', 'text-gray-300');
            btn.classList.add('bg-indigo-600', 'text-white');
            document.querySelectorAll('.item-card').forEach(card => {
                card.style.display = (cat === 'all' || card.dataset.category === cat) ? 'block' : 'none';
            });
        }
    </script>
</body>
</html>
