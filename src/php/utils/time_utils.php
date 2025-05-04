<?php
    /**
     * Converts a number value time representing minutes to proper timestamp (HH:MM:ss)
     * @param int $number
     * @return string - the timestamp
     */
    function getTime(int $number): string {
        $hours = floor($number / 60);
        $minutes = $number - ($hours * 60);

        return sprintf("%02d:%02d:00", $hours, $minutes);
    }
?>