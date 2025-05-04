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
                    case 'getMentalData':
                        getMentalData();
                        break;
                    case 'getMealData':
                        getMealData();
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
                    case 'postMealData':
                        postMealData();
                        break;
                    case 'postMentalData':
                        postMentalData();
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

    sendJsonArray($result);
}

function postMentalData() {
    $date = htmlspecialchars($_POST['date']);
    $mood = htmlspecialchars($_POST['mood']);
    $stress = htmlspecialchars($_POST['stress']);
    $sleep = htmlspecialchars($_POST['sleep']);
    $notes = htmlspecialchars($_POST['notes']);

    // Insert operation
    $conn = db_connect();
    $statement = $conn->prepare("INSERT INTO mayo_bariatric_website.mentalhealthreport VALUES (?,?,?,?,?,?)");
    $statement->bind_param('issiis', $_SESSION['id'], $date, $mood, $stress, $sleep, $notes);
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

function getMentalData() {
    $id = $_SESSION['id'];
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM mayo_bariatric_website.mentalhealthreport WHERE userId = $id");
    
    if (!$result) {
        echo 'There was an error fetching user mental health data';
        die();
    }

    sendJsonArray($result);
}

function postMealData() {
    $date = htmlspecialchars($_POST['mealDate']);
    $type = htmlspecialchars($_POST['mealType']);
    $mealDesc = htmlspecialchars($_POST['title']);
    $calories = htmlspecialchars($_POST['calories']);
    $protein = htmlspecialchars($_POST['protein']);
    $carbs = htmlspecialchars($_POST['carbs']);
    $fats = htmlspecialchars($_POST['fats']);
    $notes = htmlspecialchars($_POST['notes']);

    // Insert operation
    $conn = db_connect();
    $statement = $conn->prepare("INSERT INTO mayo_bariatric_website.mealreport VALUES (?,?,?,?,?,?,?,?,?)");
    $statement->bind_param('isssiiiis', $_SESSION['id'], $date, $type, $mealDesc, $calories, $protein, $carbs, $fats, $notes);
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

function getMealData() {
    $id = $_SESSION['id'];
    $conn = db_connect();
    $result = $conn->query("SELECT * FROM mayo_bariatric_website.mealreport WHERE userId = $id");
    
    if (!$result) {
        echo 'There was an error fetching user meal data';
        die();
    }

    sendJsonArray($result);
}

function sendJsonArray(mysqli_result $result) {
    $logs = [];
    while ($log = $result->fetch_array()) {
        $logs[] = $log;
    }
    http_response_code(200);
    echo json_encode($logs);
}
?>