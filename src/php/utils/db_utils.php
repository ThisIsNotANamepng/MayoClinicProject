<?php
    // Use __DIR__ to prevent different results from require since util called by multiple files
    $globals = require __DIR__ . "/../globals.php";

    function db_connect(): bool|mysqli {
        global $globals;
        $conn = mysqli_connect($globals['dbUrl'], $globals['dbUser'], $globals['dbPass']);

        // Check for a connection error if there is any
        if ($conn->connect_error) {
            echo 'Unable to connect to database: ' . $conn->connect_error;
            die(500);
        }

        return $conn;
    }

    function db_close(&$conn): void {
        mysqli_close($conn);
    }
?>