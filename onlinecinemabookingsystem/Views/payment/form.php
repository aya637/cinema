<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment - screenWave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #0a0a0f; color: #e5e7eb; }
        .glassmorphism { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .btn-primary { background-image: linear-gradient(to right, #4f46e5, #7c3aed); }
        .btn-primary:hover:not(:disabled) { background-image: linear-gradient(to right, #4338ca, #6d28d9); }
        .form-input { width: 100%; padding: 0.75rem; background: #1f2937; border: 1px solid #374151; border-radius: 8px; color: white; }
        .form-input:focus { outline: none; border-color: #6366f1; }
        .form-input.error { border-color: #ef4444; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <button type="button" onclick="history.back()" class="fixed top-4 left-4 z-40 bg-gray-900/80 border border-white/10 text-white px-3 py-2 rounded-full text-sm font-semibold shadow-lg hover:bg-gray-800 transition">
        ←
    </button>

    <main class="pt-24 flex-grow">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                
                <div class="lg:col-span-2">
                    <div class="glassmorphism rounded-xl p-6">
                        <h2 class="text-2xl font-bold text-white mb-6">Payment Details</h2>

                        <div class="bg-indigo-500/10 border border-indigo-500/30 rounded-lg p-4 mb-6 text-sm text-indigo-100">
                            <div class="font-semibold text-white mb-1">Signed in as</div>
                            <div><?= htmlspecialchars($_SESSION['user_name'] ?? '') ?></div>
                            <div class="text-indigo-200 text-xs"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
                        </div>
                        
                        <form id="paymentForm" action="index.php?url=payment/process" method="POST">
                            <input type="hidden" name="grand_total" value="<?= htmlspecialchars($grand_total) ?>">

                            <div class="mb-4">
                                <label class="block text-gray-300 mb-2 font-medium">Cardholder Name</label>
                                <input type="text" name="cardholder_name" class="form-input" placeholder="John Doe" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-300 mb-2 font-medium">Card Number</label>
                                <input type="text" name="card_number" id="cardNumber" class="form-input" placeholder="0000 0000 0000 0000" maxlength="19" required>
                                <p class="text-red-400 text-sm mt-1 hidden" id="cardError">Please enter a valid card number</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-gray-300 mb-2 font-medium">Expiry (MM/YY)</label>
                                    <input type="text" name="expiry" id="expiry" class="form-input" placeholder="MM/YY" maxlength="5" required>
                                    <p class="text-red-400 text-sm mt-1 hidden" id="expiryError">Please enter a valid expiry date (MM/YY)</p>
                                </div>
                                <div>
                                    <label class="block text-gray-300 mb-2 font-medium">CVC</label>
                                    <input type="text" name="cvc" id="cvc" class="form-input" placeholder="123" maxlength="3" required>
                                    <p class="text-red-400 text-sm mt-1 hidden" id="cvcError">Please enter a 3-digit CVC</p>
                                </div>
                            </div>

                            <button type="submit" class="w-full btn-primary text-white font-bold py-3 px-8 rounded-full transition duration-300">
                                Pay $<?= number_format($grand_total, 2) ?>
                            </button>
                            
                            <p class="text-center mt-4 text-sm text-gray-400">
                                <span class="mr-2">🔒</span> Secure SSL Encrypted Transaction
                            </p>
                        </form>
                    </div>
                </div>

                <div class="mt-8 lg:mt-0">
                    <div class="glassmorphism rounded-xl p-6 sticky top-24">
                        <h2 class="text-2xl font-bold text-white mb-6 border-b border-gray-700 pb-4">Booking Summary</h2>
                        
                        <div class="mb-4 pb-4 border-b border-gray-700">
                            <h4 class="font-bold text-lg text-indigo-300 mb-2">
                                <?= htmlspecialchars($cart['movie_title'] ?? 'Movie Title') ?>
                            </h4>
                            <div class="text-sm text-gray-400 mb-1">
                                Time: <span><?= isset($cart['show_time']) ? date('g:i A', strtotime($cart['show_time'])) : '' ?></span>
                            </div>
                            <div class="text-sm text-white font-semibold bg-indigo-500/10 px-2 py-1 rounded mt-2">
                                Seats: <?= htmlspecialchars($cart['seat_labels'] ?? 'Pending') ?>
                            </div>
                        </div>

                        <div id="snack-items-list" class="mb-4 pb-4 border-b border-gray-700 space-y-2 max-h-48 overflow-y-auto">
                            <!-- Snack items will be inserted here -->
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between text-gray-300 text-sm">
                                <span>Ticket Price</span>
                                <span>$<?= number_format($cart['ticket_price'] ?? 0, 2) ?></span>
                            </div>
                            <div class="flex justify-between text-gray-300 text-sm">
                                <span>Snacks & Drinks</span>
                                <span class="snack-total-value">$<?= number_format($snack_total, 2) ?></span>
                            </div>
                            <div class="flex justify-between items-center pt-3 border-t border-gray-700 mt-3">
                                <span class="font-bold text-lg text-white">Total Due</span>
                                <span class="font-bold text-2xl text-indigo-400 grand-total-value">$<?= number_format($grand_total, 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        // Card number formatting - numbers only
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            // Remove all non-numeric characters
            let value = e.target.value.replace(/\D/g, '');
            // Format with spaces every 4 digits
            let formatted = value.match(/.{1,4}/g)?.join(' ') || value;
            if (formatted.length <= 19) {
                e.target.value = formatted;
            } else {
                e.target.value = formatted.substring(0, 19);
            }
            document.getElementById('cardError').classList.add('hidden');
        });
        
        // Prevent non-numeric input
        document.getElementById('cardNumber').addEventListener('keypress', function(e) {
            if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
                e.preventDefault();
            }
        });

        // Expiry date formatting and validation with auto-format (only for months 2-9)
        let isDeleting = false;
        document.getElementById('expiry').addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' || e.key === 'Delete') {
                isDeleting = true;
            }
        });
        
        document.getElementById('expiry').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // If deleting, allow it but don't auto-format
            if (isDeleting) {
                isDeleting = false;
                // Re-format what's left
                if (value.length >= 2) {
                    value = value.substring(0, 2) + '/' + value.substring(2, 4);
                }
                e.target.value = value;
                return;
            }
            
            // Auto-format single digit month to 0X only for 2-9
            if (value.length === 1) {
                const digit = parseInt(value);
                if (digit >= 2 && digit <= 9) {
                    value = '0' + value;
                }
            }
            
            if (value.length >= 2) {
                let month = value.substring(0, 2);
                // Ensure month is 01-12
                if (parseInt(month) > 12) {
                    month = '12';
                }
                if (parseInt(month) === 0) {
                    month = '01';
                }
                value = month + '/' + value.substring(2, 4);
            }
            e.target.value = value;
            
            // Validate month (01-12) and year
            const errorEl = document.getElementById('expiryError');
            if (value.length >= 2) {
                const month = parseInt(value.substring(0, 2));
                if (month < 1 || month > 12) {
                    errorEl.textContent = 'Month must be between 01 and 12';
                    errorEl.classList.remove('hidden');
                    e.target.classList.add('error');
                } else if (value.length === 5) {
                    const year = parseInt(value.substring(3, 5));
                    const currentYear = new Date().getFullYear() % 100;
                    const currentMonth = new Date().getMonth() + 1;
                    if (year < currentYear || (year === currentYear && month < currentMonth)) {
                        errorEl.textContent = 'Card has expired';
                        errorEl.classList.remove('hidden');
                        e.target.classList.add('error');
                    } else {
                        errorEl.classList.add('hidden');
                        e.target.classList.remove('error');
                    }
                } else {
                    errorEl.classList.add('hidden');
                    e.target.classList.remove('error');
                }
            } else {
                errorEl.classList.add('hidden');
                e.target.classList.remove('error');
            }
        });

        // CVC validation (3 digits only)
        document.getElementById('cvc').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
            
            const errorEl = document.getElementById('cvcError');
            if (value.length === 3) {
                errorEl.classList.add('hidden');
                e.target.classList.remove('error');
            } else if (value.length > 0) {
                errorEl.classList.remove('hidden');
                e.target.classList.add('error');
            } else {
                errorEl.classList.add('hidden');
                e.target.classList.remove('error');
            }
        });

        // Form validation on submit
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            // Update sessionStorage with latest cart before submitting
            sessionStorage.setItem('snackCart', JSON.stringify(paymentCart));
            
            const cardNumber = document.getElementById('cardNumber').value.replace(/\s/g, '');
            const expiry = document.getElementById('expiry').value;
            const cvc = document.getElementById('cvc').value;
            
            let isValid = true;
            
            // Validate card number (at least 13 digits)
            if (cardNumber.length < 13 || cardNumber.length > 19) {
                document.getElementById('cardError').classList.remove('hidden');
                document.getElementById('cardNumber').classList.add('error');
                isValid = false;
            }
            
            // Validate expiry
            if (expiry.length !== 5) {
                document.getElementById('expiryError').textContent = 'Please enter a valid expiry date (MM/YY)';
                document.getElementById('expiryError').classList.remove('hidden');
                document.getElementById('expiry').classList.add('error');
                isValid = false;
            } else {
                const month = parseInt(expiry.substring(0, 2));
                const year = parseInt(expiry.substring(3, 5));
                const currentYear = new Date().getFullYear() % 100;
                const currentMonth = new Date().getMonth() + 1;
                
                if (month < 1 || month > 12) {
                    document.getElementById('expiryError').textContent = 'Month must be between 01 and 12';
                    document.getElementById('expiryError').classList.remove('hidden');
                    document.getElementById('expiry').classList.add('error');
                    isValid = false;
                } else if (year < currentYear || (year === currentYear && month < currentMonth)) {
                    document.getElementById('expiryError').textContent = 'Card has expired';
                    document.getElementById('expiryError').classList.remove('hidden');
                    document.getElementById('expiry').classList.add('error');
                    isValid = false;
                }
            }
            
            // Validate CVC
            if (cvc.length !== 3 || !/^\d{3}$/.test(cvc)) {
                document.getElementById('cvcError').classList.remove('hidden');
                document.getElementById('cvc').classList.add('error');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
                return false;
            }
        });

        // Global cart for payment page
        let paymentCart = [];
        
        // Load snack items from sessionStorage and enable editing
        document.addEventListener('DOMContentLoaded', function() {
            const snackCart = sessionStorage.getItem('snackCart');
            if (snackCart) {
                paymentCart = JSON.parse(snackCart);
                renderPaymentCart();
            }
        });

        function updatePaymentCartItem(index, change) {
            paymentCart[index].qty += change;
            if (paymentCart[index].qty < 1) {
                paymentCart[index].qty = 1;
            }
            if (paymentCart[index].qty > 99) {
                paymentCart[index].qty = 99;
            }
            sessionStorage.setItem('snackCart', JSON.stringify(paymentCart));
            renderPaymentCart();
            updatePaymentTotals();
        }

        function removePaymentCartItem(index) {
            paymentCart.splice(index, 1);
            sessionStorage.setItem('snackCart', JSON.stringify(paymentCart));
            renderPaymentCart();
            updatePaymentTotals();
        }


        function updatePaymentTotals() {
            let snackTotal = 0;
            paymentCart.forEach(item => {
                snackTotal += item.unitPrice * item.qty;
            });
            const ticketPrice = <?= $cart['ticket_price'] ?? 0 ?>;
            const grandTotal = ticketPrice + snackTotal;
            
            // Update display
            const snackTotalEl = document.querySelector('.snack-total-value');
            const grandTotalEl = document.querySelector('.grand-total-value');
            if (snackTotalEl) snackTotalEl.textContent = '$' + snackTotal.toFixed(2);
            if (grandTotalEl) grandTotalEl.textContent = '$' + grandTotal.toFixed(2);
            
            // Update hidden input and button text
            const grandTotalInput = document.querySelector('input[name="grand_total"]');
            if (grandTotalInput) grandTotalInput.value = grandTotal.toFixed(2);
            
            const submitBtn = document.querySelector('button[type="submit"]');
            if (submitBtn) submitBtn.textContent = 'Pay $' + grandTotal.toFixed(2);
        }
        
        // Call updatePaymentTotals after rendering cart
        function renderPaymentCart() {
            const container = document.getElementById('snack-items-list');
            if (!paymentCart || paymentCart.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-xs text-center py-2">No snacks added</p>';
            } else {
                container.innerHTML = '';
                paymentCart.forEach((item, index) => {
                    const itemTotal = item.unitPrice * item.qty;
                    const div = document.createElement('div');
                    div.className = 'flex justify-between items-start text-sm mb-2 pb-2 border-b border-gray-700/50';
                    div.innerHTML = `
                        <div class="flex-1">
                            <div class="text-white font-semibold text-xs mb-1">${item.name}</div>
                            <div class="text-gray-400 text-xs mb-2">${item.details}</div>
                            <div class="flex items-center gap-2">
                                <button type="button" onclick="updatePaymentCartItem(${index}, -1)" class="w-6 h-6 bg-gray-700 hover:bg-gray-600 rounded text-xs font-bold">−</button>
                                <span class="text-white text-xs font-semibold min-w-[20px] text-center">${item.qty}</span>
                                <button type="button" onclick="updatePaymentCartItem(${index}, 1)" class="w-6 h-6 bg-gray-700 hover:bg-gray-600 rounded text-xs font-bold">+</button>
                                <button type="button" onclick="removePaymentCartItem(${index})" class="ml-2 text-red-400 hover:text-red-300 text-xs">Remove</button>
                            </div>
                        </div>
                        <div class="text-white font-bold ml-2 text-sm">$${itemTotal.toFixed(2)}</div>
                    `;
                    container.appendChild(div);
                });
            }
            updatePaymentTotals();
        }
    </script>
</body>
</html>
