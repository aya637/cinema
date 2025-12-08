<?php
// app/controllers/HomepageController.php

require_once __DIR__ . '/Controller.php';

class HomepageController extends Controller
{
    public function index(): void
    {
        // Load the MovieHp model - use absolute path to be sure
        $movieModelPath = __DIR__ . '/../models/MovieHp.php';
        
        if (!file_exists($movieModelPath)) {
            die("MovieHp.php model not found at: " . $movieModelPath);
        }
        
        require_once $movieModelPath;
        $movieModel = new MovieHp();
        
        // Get movies from database
        $movies = $movieModel->getAllMovies('now_showing', 8); // Get up to 8 movies

        // Also load carousel posters from onlinebooking table
        $onlineBookingPath = __DIR__ . '/../models/OnlineBooking.php';
        $posters = [];
        if (file_exists($onlineBookingPath)) {
            require_once $onlineBookingPath;
            $obModel = new OnlineBooking();
            $posters = $obModel->getAllPosters(true);
        }

        $this->view('homepage/index', [
            'pageTitle' => 'Experience Cinema Like Never Before',
            'movies' => $movies,
            'posters' => $posters,
        ]);
    }
}