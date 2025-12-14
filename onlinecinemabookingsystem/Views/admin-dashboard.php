<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard - CineBook</title>
  
  <script src="https://cdn.tailwindcss.com"></script>
  
  <link rel="stylesheet" href="/onlinecinemabookingsystem/public/css/admin-dashboard.css?v=4" />

  <style>
    /* Force Buttons to look right */
    .btn { display: inline-flex !important; align-items: center; justify-content: center; border-radius: 8px !important; }
    .btn.primary { background: linear-gradient(to right, #4f46e5, #7c3aed) !important; }
    .btn.secondary { background-color: transparent !important; border: 1px solid #4b5563 !important; }
    .btn.edit { background-color: #374151 !important; color: white !important; }
    .btn.delete { background-color: rgba(239, 68, 68, 0.15) !important; color: #f87171 !important; }
    
    /* Force Tab Content Hiding */
    .tab-content { display: none !important; }
    .tab-content.active { display: block !important; }
  </style>
</head>
<body>
    
<header class="navbar">
  <div class="nav-container">
    <a href="#" class="logo">
      <img src="/onlinecinemabookingsystem/public/uploads/images/websitelogo.jpg" alt="Logo" style="height: 32px; width: 32px; border-radius: 50%; object-fit: cover;">
      <span><span class="cine">Screen</span><span class="book">Wave</span></span>
    </a>

    <nav class="nav-links">
      <a href="/onlinecinemabookingsystem/public/">Visit Website</a>
      <a href="#" class="active">Admin Panel</a>
      <a href="/onlinecinemabookingsystem/public/index.php?url=admin/logout" class="logout-btn">Logout</a>
    </nav>

    <div class="nav-icons" style="margin-left: 20px;">
      <div class="profile-icon">A</div>
    </div>
  </div>
</header>

<div class="dashboard">
    <header>
      <h1>Admin Dashboard</h1>
      <p>Manage your cinema booking system</p>
    </header>

    <nav class="tabs">
      <button class="tab active" data-tab="overview">Overview</button>
      <button class="tab" data-tab="movies">Movies</button>
      <button class="tab" data-tab="showtimes">Showtimes</button>
      <button class="tab" data-tab="bookings">Bookings</button>
      <a href="/onlinecinemabookingsystem/public/admin/sales" class="tab">Sales Report</a>
      <a href="/onlinecinemabookingsystem/public/admin/occupancy" class="tab">Occupancy Report</a>
      <a href="/onlinecinemabookingsystem/public/admin/staff" class="tab">Staff</a>
    </nav>

    <main>
      
      <section id="overview" class="tab-content active">
        <div class="stats-grid">
          <div class="card">
            <h3>Total Movies</h3>
            <p><?php echo $totalMovies ?? 0; ?></p>
          </div>
          <div class="card">
            <h3>Total Showtimes</h3>
            <p><?php echo $totalShowtimes ?? 0; ?></p>
          </div>
          <div class="card">
            <h3>Total Bookings</h3>
            <p><?php echo $totalBookings ?? 0; ?></p>
          </div>
          <div class="card">
            <h3>Total Revenue</h3>
            <p>$<?php echo number_format($totalRevenue ?? 0, 2); ?></p>
          </div>
        </div>

        <div class="glassmorphism">
          <h2>Recent Bookings</h2>
          <table>
            <thead>
              <tr><th>Booking ID</th><th>User</th><th>Amount</th><th>Date</th></tr>
            </thead>
            <tbody>
              <?php if (!empty($recentBookings)): ?>
                <?php foreach ($recentBookings as $booking): ?>
                <tr>
                  <td>#BK<?php echo $booking['id']; ?></td>
                  <td><?php echo htmlspecialchars($booking['username'] ?? 'Guest'); ?></td>
                  <td>$<?php echo number_format($booking['amount'], 2); ?></td>
                  <td><?php echo date('M d, Y', strtotime($booking['booking_date'])); ?></td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="4" style="text-align: center;">No bookings found yet.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </section> 
      <section id="movies" class="tab-content">
        <div class="section-header">
           <h2>Manage Movies</h2>
           <button class="btn primary" id="addMovieBtn">Add New Movie</button>
        </div>
    
        <div class="movie-grid">
           <?php if (!empty($movies)): ?>
               <?php foreach($movies as $movie): ?>
               <div class="movie-card">
                   <?php 
                       $imagePath = $movie['poster'] ?? 'https://via.placeholder.com/300x400';
                       if (!str_starts_with($imagePath, 'http')) {
                           $imagePath = '/onlinecinemabookingsystem/public/' . $imagePath;
                       }
                   ?>
                   <img src="<?php echo htmlspecialchars($imagePath); ?>" 
                        alt="<?php echo htmlspecialchars($movie['title']); ?>"
                        onerror="this.src='https://via.placeholder.com/300x400?text=No+Image';">
                   
                   <div class="card-content">
                       <?php 
                           $rawStatus = $movie['status'] ?? 'now_showing';
                           $displayStatus = ucwords(str_replace('_', ' ', $rawStatus)); 
                       ?>
                       <span class="movie-badge status-<?php echo $rawStatus; ?>">
                           <?php echo $displayStatus; ?>
                       </span>

                       <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                       
                       <p class="meta">
                           <?php echo htmlspecialchars($movie['rating'] ?? 'G'); ?> | 
                           <span style="color: #fff; font-weight: 500;">
                               <?php echo htmlspecialchars($movie['genre'] ?? 'Genre'); ?>
                           </span> | 
                           <?php echo $movie['duration_minutes'] ?? 0; ?>m
                       </p>
                       
                       <p class="desc"><?php echo htmlspecialchars($movie['description']); ?></p>
                       
                       <div class="actions">
                           <button class="btn edit" 
                                   data-id="<?php echo $movie['id']; ?>"
                                   data-title="<?php echo htmlspecialchars($movie['title']); ?>"
                                   data-genre="<?php echo htmlspecialchars($movie['genre'] ?? 'Action'); ?>"
                                   data-rating="<?php echo htmlspecialchars($movie['rating'] ?? 'G'); ?>"
                                   data-duration="<?php echo $movie['duration_minutes'] ?? 0; ?>"
                                   data-desc="<?php echo htmlspecialchars($movie['description']); ?>"
                                   data-poster="<?php echo htmlspecialchars($imagePath); ?>"
                                   data-status="<?php echo htmlspecialchars($rawStatus); ?>">
                               Edit
                           </button>
                           
                           <form action="/onlinecinemabookingsystem/public/Admin/deleteMovie" method="POST" style="display:inline;" onsubmit="return confirm('Delete this movie?');">
                               <input type="hidden" name="id" value="<?php echo $movie['id']; ?>">
                               <button type="submit" class="btn delete">Delete</button>
                           </form>
                       </div>
                   </div>
               </div>
               <?php endforeach; ?>
           <?php else: ?>
               <p style="grid-column: 1/-1; text-align: center;">No movies found.</p>
           <?php endif; ?>
        </div>
    
        <div id="movieModal" class="modal">
           <div class="modal-content">
               <div class="modal-header">
                   <h3>Add New Movie</h3>
                   <span class="close add-close">&times;</span>
               </div>
               <form action="/onlinecinemabookingsystem/public/admin/addMovie" method="POST" enctype="multipart/form-data">
                   <div class="form-group"><label>Movie Title:</label><input type="text" name="title" required></div>
                   <div class="form-group"><label>Genre:</label>
                      <select name="genre" required>
                        <option value="Action">Action</option>
                        <option value="Adventure">Adventure</option>
                        <option value="Comedy">Comedy</option>
                        <option value="Drama">Drama</option>
                        <option value="Horror">Horror</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Animation">Animation</option>
                        <option value="Romance">Romance</option>
                      </select>
                  </div>
                  <div class="form-group"><label>Rating:</label>
                    <select name="rating" required>
                        <option value="G">G</option><option value="PG">PG</option><option value="PG-13">PG-13</option><option value="R">R</option>
                    </select>
                  </div>
                   <div class="form-group"><label>Duration:</label><input type="number" name="duration" required></div>
                   <div class="form-group"><label>Description:</label><textarea name="description" required></textarea></div>
                   <div class="form-group"><label>Poster:</label><input type="file" name="poster" id="addPosterInput" required><div class="preview-box" id="addPosterPreview"></div></div>
                   <div class="form-group"><label>Status:</label>
                         <select name="status" required>
                          <option value="now_showing">Now Showing</option>
                          <option value="coming">Coming Soon</option>
                          <option value="archived">Archived</option>
                         </select>
                    </div>
                   <button type="submit" class="btn primary" style="width:100%">Create Movie</button>
               </form>
           </div>
        </div>
    
        <div id="editMovieModal" class="modal">
           <div class="modal-content">
               <div class="modal-header">
                   <h3>Edit Movie</h3>
                   <span class="close edit-close">&times;</span>
               </div>
               <form action="/onlinecinemabookingsystem/public/admin/updateMovie" method="POST" enctype="multipart/form-data">
                   <input type="hidden" name="id" id="editId">
                   <div class="form-group"><label>Title:</label><input type="text" name="title" id="editTitle" required></div>
                   <div class="form-group"><label>Genre:</label>
                      <select name="genre" id="editGenre" required>
                          <option value="Action">Action</option><option value="Adventure">Adventure</option><option value="Comedy">Comedy</option><option value="Drama">Drama</option><option value="Horror">Horror</option><option value="Sci-Fi">Sci-Fi</option><option value="Animation">Animation</option><option value="Romance">Romance</option>
                      </select>
                  </div>
                  <div class="form-group"><label>Rating:</label>
                      <select name="rating" id="editRating" required>
                          <option value="G">G</option><option value="PG">PG</option><option value="PG-13">PG-13</option><option value="R">R</option>
                      </select>
                  </div>
                   <div class="form-group"><label>Duration:</label><input type="number" name="duration" id="editDuration" required></div>
                   <div class="form-group"><label>Description:</label><textarea name="description" id="editDesc" required></textarea></div>
                   <div class="form-group"><label>Poster:</label><input type="file" name="poster" id="editPosterInput"><div class="preview-box" id="editPosterPreview"></div>
                       <div class="form-group"><label>Status:</label>
                         <select name="status" id="editStatus" required>
                          <option value="now_showing">Now Showing</option><option value="coming">Coming Soon</option><option value="archived">Archived</option>
                          </select>
                        </div>
                   </div>
                   <button type="submit" class="btn primary" style="width:100%">Save Changes</button>
               </form>
           </div>
        </div>
      </section>
      <section id="showtimes" class="tab-content">
        <div class="section-header">
           <h2>Manage Showtimes</h2>
           <button class="btn primary" id="addShowtimeBtn">Schedule Movie</button>
        </div>
         
        <div class="glassmorphism">
           <table>
               <thead>
                   <tr><th>Movie Title</th><th>Date</th><th>Time</th><th>Theater</th><th>Seats</th><th>Price</th><th>Actions</th></tr>
               </thead>
               <tbody>
                   <?php if (!empty($showtimes)): ?>
                       <?php foreach($showtimes as $show): ?>
                       <tr>
                           <td><strong><?php echo htmlspecialchars($show['title']); ?></strong></td>
                           <td><?php echo date('M d, Y', strtotime($show['show_time'])); ?></td>
                          <td><?php echo date('g:i A', strtotime($show['show_time'])); ?></td>
                           <td><?php echo htmlspecialchars($show['screen_name']); ?></td>
                           <td>
                               <span style="font-weight: bold; <?php echo ($show['available_seats'] < 10) ? 'color: red;' : 'color: green;'; ?>">
                                   <?php echo $show['available_seats']; ?>
                               </span> / 50
                           </td>
                           <td>$<?php echo number_format($show['price'], 2); ?></td>
                           <td>
                               <form action="/onlinecinemabookingsystem/public/Admin/deleteShowtime" method="POST" style="display:inline;" onsubmit="return confirm('Remove this showtime?');">
                                   <input type="hidden" name="id" value="<?php echo $show['id']; ?>">
                                   <button type="submit" class="btn delete">Remove</button>
                               </form>
                           </td>
                       </tr>
                       <?php endforeach; ?>
                   <?php else: ?>
                       <tr><td colspan="7" style="text-align:center;">No showtimes scheduled yet.</td></tr>
                   <?php endif; ?>
               </tbody>
           </table>
        </div>
    
        <div class="modal" id="showtimeModal">
          <div class="modal-content">
            <div class="modal-header"><h3>Add Showtime</h3><span class="close showtime-close">&times;</span></div>
            <form action="/onlinecinemabookingsystem/public/admin/addShowtime" method="POST">
              <div class="form-group"><label>Movie</label>
                  <select id="showtimeMovie" name="movie_id" required>
                    <option value="">Select Movie</option>
                    <?php if (!empty($movies)): foreach ($movies as $movie): ?>
                        <option value="<?= $movie['id'] ?>"><?= htmlspecialchars($movie['title']) ?></option>
                    <?php endforeach; endif; ?>
                  </select>
              </div>
              <div class="form-group"><label>Theater</label>
                  <select name="screen_name" required>
                    <option value="1">Hall A - Standard</option>
                    <option value="2">Hall B - IMAX</option>
                    <option value="3">VIP Lounge</option>
                  </select>
              </div>
              <div class="form-group"><label>Date</label><input type="date" id="showtimeDate" name="date" required></div>
              <div class="form-group"><label>Time</label><input type="time" id="showtimeTime" name="time" required></div>
              <div class="form-group"><label>Ticket Price</label><input type="number" id="showtimePrice" name="price" step="0.01" value="12.00" required></div>
              <div class="modal-actions" style="margin-top: 20px; display:flex; gap:10px;">
                <button type="submit" class="btn primary">Save Showtime</button>
                <button type="button" class="btn secondary showtime-close">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </section>
      <section id="bookings" class="tab-content">
        <div class="section-header"><h2>All Bookings History</h2></div>
        <div class="glassmorphism">
           <table>
               <thead><tr><th>Booking ID</th><th>User</th><th>Movie</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
               <tbody>
                   <?php if (!empty($allBookings)): ?>
                       <?php foreach($allBookings as $b): ?>
                       <tr>
                           <td>#BK<?php echo $b['id']; ?></td>
                           <td><?php echo htmlspecialchars($b['username'] ?? 'Guest'); ?></td>
                           <td><?php echo htmlspecialchars($b['title']); ?></td>
                           <td><?php echo date('M d, Y h:i A', strtotime($b['booking_date'])); ?></td>
                           <td>$<?php echo number_format($b['amount'], 2); ?></td>
                           <td><span class="status <?php echo strtolower($b['status']); ?>"><?php echo ucfirst($b['status']); ?></span></td>
                       </tr>
                       <?php endforeach; ?>
                   <?php else: ?>
                       <tr><td colspan="6" style="text-align: center;">No bookings found.</td></tr>
                   <?php endif; ?>
               </tbody>
           </table>
        </div>
      </section>
      </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // --- TABS LOGIC (FIXED) ---
    const tabs = document.querySelectorAll(".tab");
    const contents = document.querySelectorAll(".tab-content");
    
    tabs.forEach(tab => {
        // Only run logic if it's a BUTTON (not a link like Sales/Staff)
        if(tab.tagName === 'BUTTON') {
            tab.addEventListener("click", () => {
                // 1. Remove active class from all tabs
                tabs.forEach(t => t.classList.remove("active"));
                // 2. Hide all content sections
                contents.forEach(c => c.classList.remove("active"));
                
                // 3. Activate clicked tab
                tab.classList.add("active");
                // 4. Show corresponding content
                const targetId = tab.getAttribute("data-tab");
                const targetContent = document.getElementById(targetId);
                if(targetContent) {
                    targetContent.classList.add("active");
                }
            });
        }
    });

    // --- MODAL LOGIC ---
    const addMovieBtn = document.getElementById("addMovieBtn");
    const movieModal = document.getElementById("movieModal");
    const closeMovie = document.querySelector(".add-close");
    const moviePosterFile = document.getElementById("addPosterInput"); 
    const posterPreview = document.getElementById("addPosterPreview"); 
    const addMovieForm = movieModal.querySelector("form");

    if (addMovieBtn) {
        addMovieBtn.onclick = () => {
            if (addMovieForm) addMovieForm.reset();
            if (posterPreview) posterPreview.innerHTML = "";
            movieModal.style.display = "flex";
        };
    }
    
    if (closeMovie) closeMovie.onclick = () => (movieModal.style.display = "none");

    if (moviePosterFile) {
        moviePosterFile.addEventListener("change", function () {
            const file = this.files[0];
            if (file) posterPreview.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="Preview" style="max-width:100px; margin-top:10px;">`;
        });
    }

    // --- EDIT MODAL ---
    const editModal = document.getElementById("editMovieModal");
    const editClose = document.querySelector(".edit-close");
    const editButtons = document.querySelectorAll(".btn.edit");

   editButtons.forEach(button => {
    button.addEventListener("click", () => {
        document.getElementById("editId").value = button.getAttribute("data-id");
        document.getElementById("editTitle").value = button.getAttribute("data-title");
        document.getElementById("editDuration").value = button.getAttribute("data-duration");
        document.getElementById("editDesc").value = button.getAttribute("data-desc");
        
        document.getElementById("editGenre").value = button.getAttribute("data-genre");
        document.getElementById("editRating").value = button.getAttribute("data-rating");
        document.getElementById("editStatus").value = button.getAttribute("data-status");

        const poster = button.getAttribute("data-poster");
        document.getElementById("editPosterPreview").innerHTML = `<img src="${poster}" style="max-width:100px; margin-top:10px;">`;

        editModal.style.display = "flex";
    });
});

    if (editClose) editClose.onclick = () => (editModal.style.display = "none");

    // --- SHOWTIME MODAL ---
    const addShowtimeBtn = document.getElementById("addShowtimeBtn");
    const showtimeModal = document.getElementById("showtimeModal");
    const closeShowtimeElements = document.querySelectorAll(".showtime-close");

    if (addShowtimeBtn) {
        addShowtimeBtn.onclick = () => (showtimeModal.style.display = "flex");
    }
    
    closeShowtimeElements.forEach(el => {
        el.onclick = () => (showtimeModal.style.display = "none");
    });

    // --- GLOBAL CLICK TO CLOSE ---
    window.onclick = function (e) {
        if (e.target === movieModal) movieModal.style.display = "none";
        if (e.target === editModal) editModal.style.display = "none";
        if (e.target === showtimeModal) showtimeModal.style.display = "none";
    };
});
</script>
</body>
</html>