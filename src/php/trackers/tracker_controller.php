<?php
require '../utils/db_utils.php';
require '../utils/activity_utils.php';
require '../utils/time_utils.php';

session_start();

// Assume 500 until specified otherwise
http_response_code(500);
doAction();

/* Rest controller for CRUD operations on trackers and reports */
function doAction(): void {
    $action = $_GET['action'];
    switch ($_SERVER['REQUEST_METHOD']) {
        case "GET":
            if (isset($action)) {
                switch ($action) {
                    case 'getFitnessData':
                        getFitnessData();
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
                    case 'postFitnessData':
                        postFitnessData();
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
        default:
            echo 'Invalid request method ' . $_SERVER['REQUEST_METHOD'];
            break;
    }
}

/**
 * Inserts posted fitness data into database
 * @return void
 */
function postFitnessData() {
    $date = htmlspecialchars($_POST['date']);
    $weight = htmlspecialchars($_POST['weight']);
    $activity = htmlspecialchars($_POST['activity']);
    $duration = htmlspecialchars($_POST['length']);

    $timeStampDuration = getTime($duration);

    // Insert operation
    $conn = db_connect();
    $statement = $conn->prepare("INSERT INTO mayo_bariatric_website.exercisereport VALUES (?,?,?,?,?)");
    $statement->bind_param('issis', $_SESSION['id'], $activity, $timeStampDuration, $weight, $date);
    $result = $statement->execute();
    db_close($conn);

    if ($result) {
        http_response_code(200);
        echo 'User data uploaded successfully!';
    } else {
        echo 'There was an error inserting user data into the database!';
        die();
    }
}

function getFitnessData() {
    $id = $_SESSION['id'];
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM mayo_bariatric_website.exercisereport WHERE userId = $id");
    
    if (!$result) {
        echo 'There was an error fetching user exercise data';
        die();
    }

    $logs = [];
    while ($log = $result->fetch_array()) {
        $logs[] = $log;
    }
    http_response_code(200);
    echo json_encode($logs);
}
?>