<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking - Home</title>
    <link rel="stylesheet" href="HomePage.css">
</head>
<body>


    <header>
        <div class="header-content">
            <div>
                <h1>🎬 CineBook</h1>
                <p class="tagline">Your premier cinema booking experience</p>
            </div>
        </div>
            <nav class="navbar">
  <div class="nav-container">   
    <ul class="nav-links">
      <li><a href="HomePage.html">Home</a></li>
      <li><a href="movies.html">Movies</a></li>
      <li><a href="LoginPage.html">Login</a></li>
      <li><a href="RegistrationPage.html">Register</a></li>
      <li><a href="#">Contact</a></li>
    </ul>
  </div>
</nav>
    </header>

    <main>
        <div class="intro">
            <h2>Now Showing</h2>
            <p>Select a movie and book your tickets today</p>
        </div>

        <div id="movies-container" class="movies-grid">
            <!-- Movies will be loaded here -->
        </div>
    </main>

    <div id="bookingModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Book Your Tickets</h3>
                <p class="modal-subtitle" id="modalMovieTitle"></p>
            </div>
            <div class="modal-body">
                <div id="bookingForm">
                    <form id="ticketForm">
                        <div class="form-group">
                            <label class="form-label">⏰ Select Showtime</label>
                            <select id="showtime" class="form-select" required>
                                <!-- Showtimes will be populated -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">🎫 Number of Tickets</label>
                            <input type="number" id="numTickets" class="form-input" min="1" max="10" value="1" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">👤 Your Name</label>
                            <input type="text" id="customerName" class="form-input" placeholder="John Doe" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">📧 Email Address</label>
                            <input type="email" id="customerEmail" class="form-input" placeholder="john@example.com" required>
                        </div>

                        <div class="price-display">
                            <span class="price-label">Total Price:</span>
                            <span class="price-amount" id="totalPrice">$0.00</span>
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                            <button type="submit" class="btn-confirm">Confirm Booking</button>
                        </div>
                    </form>
                </div>
                <div id="successMessage" class="success-message" style="display: none;">
                    <div class="success-icon">✓</div>
                    <h4>Booking Confirmed!</h4>
                    <p>Check your email for confirmation details.</p>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 CineBook. Experience cinema like never before.</p>
    </footer>


</body>
</html>
