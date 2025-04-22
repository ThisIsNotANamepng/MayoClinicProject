<?php
    require '../utils/db_utils.php';
    require '../utils/height_utils.php';
    session_start();

    doAction();

    function doAction(): void {
        switch ($_SERVER['REQUEST_METHOD']) {
            case "GET":
                $action = $_GET['action'];
                if (isset($action)) {
                    switch ($action) {
                        case 'getDetails':
                            populateDetails();
                            break;
                        case 'getSettings':
                            populateSettings();
                            break;
                        default:
                            echo 'Invalid action ' . $action;
                            die(500);
                    }
                } else {
                    die(500);
                }
                break;
            case "POST":
                insertNewSettings();
            default:
                echo 'Invalid request method ' . $_SERVER['REQUEST_METHOD'];
                break;
        }
    }

    /**
     * Returns account id and email to be displayed in user details section
     * @return void
     */
    function populateDetails() {
        $details = array(
            'id' => $_SESSION['id'],
            'email' => $_SESSION['email']
        );

        $response = json_encode($details);
        header('Content-Type: application/json; charset=utf-8');
        echo $response;
        http_response_code(200);
    }

    /**
     * Fetches user details entry from database and returns as a json response to be populated in settings
     * @return void
     */
    function populateSettings() {
         // Get the session id
         $id = $_SESSION['id'];

         // Query db for user details
         $conn = db_connect();
         $statement = $conn->prepare(
            'SELECT * FROM mayo_bariatric_website.userdetails WHERE userID = ?'
         );
         $statement->bind_param('i', $id);
         $statement->execute();
         $resultArr = $statement->get_result()->fetch_array();

         // add first name, height feet, and height inches to response
         $resultArr['firstName'] = $_SESSION['name'];
         $resultArr['heightFeet'] = getFeet($resultArr['height']);
         $resultArr['heightInches'] = getInches($resultArr['height']);

         // Parse as json and return
         $response = json_encode($resultArr);
         header('Content-Type: application/json; charset=utf-8');
         echo $response;
         http_response_code(200);
         db_close($conn);
    }

    /**
     * Inserts new settings into the database
     */
    function insertNewSettings() {
        
        $conn = db_connect();

        $statement = $conn->prepare(
            'UPDATE mayo_bariatric_website.account
            SET name = ?
            WHERE id = ?'
        );

        // Begin transaction
        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        $statement->bind_param(
            'sd',
            $_SESSION['name'],
            $_SESSION['id']
        );

        $result1 = $statement->execute();

        // Insert user details
        $statement = $conn->prepare(
            'UPDATE mayo_bariatric_website.userdetails 
            SET dob = ?, lastName = ?, height = ?, startWeight = ?, allergies = ?, conditions = ?, medicalHistory = ?
            WHERE userID = ?'
        );

        $heightDecimal = toDecimal($_POST['heightFeet'], $_POST['heightInches']);

        $dob = htmlspecialchars($_POST['dob']);
        $lastName = htmlspecialchars($_POST['lname']);
        $allergies = htmlspecialchars($_POST['allergies']);
        $conditions = htmlspecialchars($_POST['conditions']);
        $history = htmlspecialchars($_POST['history']);
        $statement->bind_param(
            'ssddsssd',
            $dob,
            $lastName,
            $heightDecimal,
            $_POST['startWeight'],
            $allergies,
            $conditions,
            $history,
            $_SESSION['id']
        );

        $result2 = $statement->execute();

        // Commit / end transaction
        $conn->commit();
        
        if ($result1 && $result2) {
            echo 'User details updated successfully';
            http_response_code(200);
        } else {
            echo 'An error occured updating the user details';
            http_response_code(500);
        }

        db_close($conn);
    }
?>