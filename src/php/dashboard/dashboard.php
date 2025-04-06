<?php
     session_start();

    doAction();

    function doAction() {
        if (isset($_GET['action'])) {
            switch($_GET['action']) {
                case 'getName':
                    getName();
                    break;
                default:
                    echo 'Invalid action: ' . $_GET['action'];
                    die(500);
            }
        } else {
            echo 'No valid action provided!';
            die(500);
        }
    }

    /**
     * Sends response with name associated with the session
     */
    function getName(): void {
        if (isset($_SESSION['name'])) {
            echo $_SESSION['name'];
            http_response_code(200);
        } else {
            echo 'Unknown';
            http_response_code(500);
        }
    }
?>