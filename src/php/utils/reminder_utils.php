<?php
    /**
     * Retrieves an array of reminder data
     * @param int $maxResults - The maximum results to return in array
     * @return array|bool|null
     */
    function fetch_reminders(int $maxResults) {
        if (!isset($_SESSION['id'])) {
            return false;
        }

        $id = $_SESSION['id'];

        $conn = db_connect();

        $result = $conn->query("SELECT * FROM mayo_bariatric_website.reminder WHERE userId = '$id' ORDER BY dueDate DESC LIMIT $maxResults");

        db_close($conn);

        if ($result) {
            $reminders = [];
            while ($reminder = $result->fetch_array()) {
                $reminders[] = $reminder;
            }
            return $reminders;
        } else {
            return false;
        }
    }
?>