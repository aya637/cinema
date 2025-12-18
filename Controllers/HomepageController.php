<?php
// Controllers/HomepageController.php
// UPDATED VERSION - Uses 'movies' table instead of 'moviehomepage'

class HomepageController extends Controller
{
    public function index(): void
    {
        // Load the Movie model (uses 'movies' table)
        $movieModel = $this->model('Movie');
        
        // Get 8 movies with status 'now_showing' from the movies table
        $movies = $movieModel->getAll(1, 8, 'now_showing');

        // Initialize empty arrays for posters (optional - not used anymore but kept for backwards compatibility)
        $posters = [];

        // Initialize booking arrays to prevent undefined variable errors
        $currentBookings = [];
        $pastBookings = [];

        // Load view with all data
        $this->view('homepage/index', [
            'pageTitle' => 'Experience Cinema Like Never Before',
            'movies' => $movies,
            'posters' => $posters,
            'currentBookings' => $currentBookings,
            'pastBookings' => $pastBookings,
        ]);
    }
}
?>