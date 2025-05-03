<?php
    require '../utils/db_utils.php';
    require '../utils/activity_utils.php';
    require '../utils/reminder_utils.php';

    // Assume unsuccessful until specified otherwise
    http_response_code(500);

    session_start();

    doAction();

    function doAction() {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'getName':
                    getName();
                    break;
                case 'getActivity':
                    if (!isset($_GET['maxResults'])) {
                        echo 'maxResults for activity not specified';
                        die();
                    }
                    getActivity($_GET['maxResults']);
                    break;
                case 'getReminders':
                    if (!isset($_GET['maxResults'])) {
                        echo 'maxResults for reminders not specified';
                        die();
                    }
                    getReminders($_GET['maxResults']);
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

    /**
     * Sends response with name associated with the session
     */
    function getName(): void {
        if (isset($_SESSION['name'])) {
            echo $_SESSION['name'];
            http_response_code(200);
        } else {
            echo 'Unknown';
        }
    }

    /**
     * Retrieves up to maxResults userActivity records and returns as json
     */
    function getActivity($maxResults) {
        $activityLogs = get_activity($maxResults);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($activityLogs);
        http_response_code(200);
    }

    /**
     * Retrieves up to maxResults reminder records and returns as json
     */
    function getReminders($maxResults) {
        $reminders = fetch_reminders($maxResults);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($reminders);
        http_response_code(200);
    }
?>