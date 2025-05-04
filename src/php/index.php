<?php
    require './utils/db_utils.php';
    require './utils/height_utils.php';
    require './utils/activity_utils.php';

    // Assume 500 unless specified otherwise
    http_response_code(500);

    session_start();

    doAction();

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
                die();
            }
        } else {
            echo 'No valid action provided!';
            die();
        }
    }

    function login(): void {
        $conn = db_connect();

        // Access the submitted form details
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        // Validate credentials
        $statement = $conn->prepare(
        'SELECT password 
                FROM mayo_bariatric_website.account 
                WHERE email = ?'
        );

        $statement->bind_param('s', $email);
        $statement->execute();
    
        $result = $statement->get_result();
        $storedPassword = "";
        if ($result->num_rows > 0) {
            $storedPassword = $result->fetch_assoc()['password'];
        } else {
            echo 'Could not identify account with the given email';
            http_response_code(404);
            die();
        }

        // Return whether the creds are valid
        if (password_verify($password, $storedPassword)) {
            echo 'Login Successful!';

            // Create Session
            setSession($email);

            // Create activity log
            post_activity(Activity_Status::LOGIN);
           
            http_response_code(200);
        } else {
           // Send error response [Unauthorized]
           echo 'Invalid password provided!';
           http_response_code(401);
        }

        db_close($conn);
    }

    function signUp(): void {
        $conn = db_connect();
        $password = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);
        $email = htmlspecialchars($_POST['email']);
        $fName = htmlspecialchars($_POST['fName']);
        $lName = htmlspecialchars($_POST['lName']);
        $hFeet = $_POST['hFeet'];
        $hInch = $_POST['hInch'];
        $weight = $_POST['weight'];

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
            db_close($conn);
            return;
        }

        // Commit changes / end transaction
        $conn->commit();

        echo 'Account created successfully!';

        // Create Session for new account user
        setSession($email);

        // Update activity
        post_activity(Activity_Status::CREATE_ACCT);

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