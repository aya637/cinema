<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - screenWave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #0a0a0f; 
            color: #e5e7eb; 
            margin: 0;
            min-height: 100vh;
        }
        .glassmorphism { 
            background: rgba(255, 255, 255, 0.05); 
            backdrop-filter: blur(10px); 
            -webkit-backdrop-filter: blur(10px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
        }
        .btn-primary { 
            background-image: linear-gradient(to right, #4f46e5, #7c3aed); 
        }
        .btn-primary:hover { 
            background-image: linear-gradient(to right, #4338ca, #6d28d9); 
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col">

    <main class="pt-24 flex-grow flex items-center justify-center py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto">
                <div class="glassmorphism rounded-xl p-8 text-center">
                    
                    <div class="mb-6">
                        <div class="w-20 h-20 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2">Payment Successful</h1>
                        <p class="text-gray-400">Your booking has been confirmed</p>
                    </div>

                    <div class="bg-gray-900/50 rounded-lg p-6 mb-6 text-left">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center pb-3 border-b border-gray-700">
                                <span class="text-gray-400">Booking ID</span>
                                <span class="text-indigo-400 font-bold text-lg">#<?= htmlspecialchars($booking_id) ?></span>
                            </div>
                            <div class="flex justify-between items-center pb-3 border-b border-gray-700">
                                <span class="text-gray-400">Payment Reference</span>
                                <span class="text-white font-mono text-sm"><?= htmlspecialchars($payment_ref) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400">Amount Paid</span>
                                <span class="text-green-400 font-bold text-xl">$<?= number_format($amount, 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-indigo-500/10 border border-indigo-500/20 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-300">
                            A confirmation email with your booking details has been sent to your registered email address.
                        </p>
                    </div>

                    <a href="index.php?url=home" class="inline-block btn-primary text-white font-bold py-3 px-8 rounded-full transition duration-300">
                        Return Home
                    </a>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
