<?php

class DBConexion {
    public static function connection() {
        // Using static connection instead of instance property to reduce overhead
        $connection = new mysqli("localhost", "root", "", "products");
        
        if ( $connection->errno ) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        } else {
            // Set UTF-8 encoding to ensure proper character handling across the application
            $connection->set_charset("utf8mb4");
            return $connection;
        }
    }
}