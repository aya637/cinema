<?php
// app/models/Model.php
class Model {
    protected $db;

    public function __construct() {
        // Database connection parameters from config
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->db = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // Stop execution if DB fails
            die("<p style='color:red'><strong>Database Connection Failed:</strong> " . $e->getMessage() . "</p>");
        }
    }
}