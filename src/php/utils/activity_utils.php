<?php

    /**
     * Contains status codes to be used for post_activity function param
     */
    enum Activity_Status {
        case LOGIN;
        case SETTINGS_CHANGED;
        case REMINDER_SET;
        case MEAL_TRACK;
        case FIT_TRACK;
        case MENTAL_TRACK;

    }

    
    /**
     * Creates an activity record for the given status
     */
    function post_activity(Activity_Status $status): bool {
        if(!isset($_SESSION['id'])) {
            session_start();
        }

        $activityType = "";
        switch($status) {
            case Activity_Status::LOGIN:
                $activityType = "Logged In";
                break;
            case Activity_Status::SETTINGS_CHANGED:
                $activityType = "Changed Settings";
                break;
            case Activity_Status::REMINDER_SET:
                $activityType = "Set a reminder";
                break;
            case Activity_Status::MEAL_TRACK:
                $activityType = "Tracked a meal";
                break;
            case Activity_Status::FIT_TRACK:
                $activityType = "Tracked a workout";
                break;
            case Activity_Status::MENTAL_TRACK:
                $activityType = "Tracked mental health";
                break;
            default:
                return false;
        }

        $conn = db_connect();

        $id = $_SESSION['id'];
        if(!isset($id)) {
            return false;
        }
        $date = date('Y-m-d H:i:s');
        $result = $conn->query("INSERT INTO mayo_bariatric_website.useractivity VALUES ('$id', '$activityType', '$date')");

        if(!$result) {
            return false;
        }

        db_close($conn);

        return true;
    }
?>