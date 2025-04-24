<?php
session_start();
isLoggedIn();

// Returns whether the requesting user is logged in (200) or not (401)
function isLoggedIn(): void {
    if (isset($_SESSION['id'])) {
        http_response_code(200);
    } else {
        http_response_code(401);
    }
}
?>