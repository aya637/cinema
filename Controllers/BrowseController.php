<?php
// app/Controllers/BrowseController.php

class BrowseController extends Controller {
    protected $movieModel;

    public function __construct() {
        // Ensure the model name matches the file name exactly
        $this->movieModel = $this->model('Movie');
    }

    // GET /movies
    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $status = $_GET['status'] ?? 'now_showing';
        $per = isset($_GET['per']) ? (int)$_GET['per'] : 12;

        $movies = $this->movieModel->getAll($page, $per, $status);

        $this->view('browse/list', [
            'movies' => $movies,
            'currentPage' => $page,
            'status' => $status
        ]);
    }

    // GET /movies/{id}
    public function show($id) {
        $movie = $this->movieModel->getById($id);
        
        if (!$movie) {
            // Simple 404 handling
            http_response_code(404);
            echo "Movie not found.";
            exit;
        }
        
        $showtimes = $this->movieModel->getShowtimes($id);
        $this->view('browse/details', ['movie' => $movie, 'showtimes' => $showtimes]);
    }
}