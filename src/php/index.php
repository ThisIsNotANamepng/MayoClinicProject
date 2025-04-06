<?php
    require "./utils/height_utils.php";
    $globals = require "globals.php";

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        doAction();
    }

    function doAction(): void {
        // Listen for events to dispatch
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
            case 'login':
                login();
                break;
            case 'signup':
                signUp();
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

    function db_close($conn): void {
        mysqli_close($conn);
    }

    function login(): void {
        $conn = db_connect();

        // Access the submitted form details
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        // Validate credentials
        $statement = $conn->prepare(
        'SELECT * 
                FROM mayo_bariatric_website.account 
                WHERE email = ? AND password = ?'
        );

        $statement->bind_param('ss', $email, $password);
        $statement->execute();
        
        // Return whether the creds are valid
        if ($statement->get_result()->num_rows == 1) {
            echo 'Login Successful!';
           
            // Create Session
            setSession($email);
           
            http_response_code(200);
        } else {
           // Send error response [Unauthorized]
           echo 'Invalid email and/or password provided!';
           http_response_code(401);
        }

        db_close($conn);
    }

    function signUp(): void {
        $conn = db_connect();

        $password = $_POST['password'];
        $email = $_POST['email'];
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $hFeet = $_POST['hFeet'];
        $hInch = $_POST['hInch'];

        // Get the height decimal
        $height = toDecimal($hFeet,$hInch);
        // Use for fields that are not configured in the sign up
        $blank = null;
 
        // Check that this email doesnt already have account
        $statement = $conn->prepare(
            'SELECT *
                    FROM mayo_bariatric_website.account
                    WHERE email = ?'
        );

        $statement->bind_param('s',$email);
        $statement->execute();

        if ($statement->get_result()->num_rows > 0) {
            echo 'This email already has an account!';
            http_response_code(409);
            db_close($conn);
            return;
        }

        // Begin transaction
        $conn->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        // Create account
        $statement = $conn->prepare(
            'INSERT INTO mayo_bariatric_website.account VALUES (?,?,?,?)'
        );

        $statement->bind_param('isss', $blank,  $email, $password, $fName);
        
        if (!($statement->execute())) {
            echo 'Error creating account: ' . $statement->error;
            http_response_code(500);
            db_close($conn);
            return;
        }
        
        // Using the newly created userid fill details
        $statement = $conn->prepare(
            'INSERT INTO mayo_bariatric_website.userdetails VALUES (
                    (SELECT id FROM mayo_bariatric_website.account 
                     WHERE email = ?),?,?,?,?,?,?,?)'
        );

        $statement->bind_param('sssddsss', 
            $email,
            $blank,
            $lName,
            $height,
            $weight,
            $blank,
            $blank,
            $blank
        );

        if (!$statement->execute()) {
            echo 'Error creating account details: ' . $statement->error;
            http_response_code(500);
            db_close($conn);
            return;
        }

        // Commit changes / end transaction
        $conn->commit();

        echo 'Account created successfully!';

        // Create Session for new account user
        setSession($email);

        http_response_code(200);
        db_close($conn);
    }

    /**
     * Sets the session details for the given email
     * @param string $email - the email to set session with
     */
    function setSession($email){
        // Find name for associated user email
        $conn = db_connect();
        $statement = $conn->prepare(
            'SELECT id,name FROM mayo_bariatric_website.account WHERE email = ?'
        );
        $statement->bind_param('s', $email);
        $statement->execute();
        $resultArr = $statement->get_result()->fetch_array();
        $id = $resultArr['id'];
        $name = $resultArr['name'];

        // Set session with stored email, user name, and id
        $_SESSION['id'] = $id;
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;

    }
?>