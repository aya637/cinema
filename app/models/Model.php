<?php
// app/models/Model.php

class Model {
    protected $db;

    public function __construct() {
        // DELETE THIS LINE: require _DIR_ . '/../config/db.php'; 
        // We don't need it because index.php already loaded it!

        // Database connection logic
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->db = new PDO($dsn, DB_USER, DB_PASS, $options);
            // Comment out this echo so it doesn't mess up your HTML layout later
            // echo "<p style='color:lightgreen'>DATABASE CONNECTED ✔</p>";
        } catch (PDOException $e) {
            die("<p style='color:red'><strong>DATABASE CONNECTION FAILED:</strong> " . $e->getMessage() . "</p>");
        }
    }
}