<?php
    require '../utils/db_utils.php';
    require '../utils/reminder_utils.php';
    require '../utils/activity_utils.php';

    session_start();

    // Assume error unless specified otherwise
    http_response_code(500);

    doAction();

    function doAction(): void {
        $action = $_GET['action'];
        switch ($_SERVER['REQUEST_METHOD']) {
            case "GET":
                if (isset($action)) {
                    switch ($action) {
                        case 'getReminders':
                            getReminders();
                            break;
                        default:
                            echo 'Invalid action ' . $action;
                            die();
                    }
                } else {
                    echo 'No action provided';
                    die();
                }
                break;
            case "POST":
                if (isset($action)) {
                    switch ($action) {
                        case 'postReminder':
                            postReminder();
                            break;
                        default:
                            echo 'Invalid action ' . $action;
                            die();
                    }
                } else {
                    echo 'No action provided';
                    die();
                }
            default:
                echo 'Invalid request method ' . $_SERVER['REQUEST_METHOD'];
                break;
        }
    }

    /**
     * Fetches reminders from database and returns to client as json
     * @return void
     */
    function getReminders() {
        // Fetch up to 10 reminders from database
        $reminders = fetch_reminders(10);
    
        if ($reminders) {
            echo json_encode($reminders);
            http_response_code(200);
        } else {
            echo 'Error retrieving reminders from database';
            die();
        }
    }

    /**
     * Inserts new reminder form data into database
     * @return void
     */
    function postReminder() {
        if (!isset($_SESSION['id'])) {
            return;
        }
        $id = $_SESSION['id'];
        $task = htmlspecialchars($_POST['task']);
        $due = htmlspecialchars($_POST['due']);

        $conn = db_connect();
        
        $due = str_replace("T"," ",$due);

        $result = $conn->query("INSERT INTO mayo_bariatric_website.reminder VALUES ('$id','$task','$due')");
        db_close($conn);

        if ($result) {
            // Activity log update
            post_activity(Activity_Status::REMINDER_SET);

            echo 'Successfully submitted reminder into database';
            http_response_code(200);
        } else {
            echo 'Failed to enter reminder into database';
            die();
        }
    }
?>