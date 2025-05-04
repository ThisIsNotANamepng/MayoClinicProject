/**
 * Formats the utc date to local date and time
 * @param date - the date to format
 * @returns the foramtted time
 */
function formatDateTime(date) {
    // Adding " GMT+0200" the date object will understand to convert to local time
    // For some reason the implementation is giving back CEST time zo
    let formattedDate = new Date(date + " GMT+0200");
    return formattedDate.toLocaleDateString() + " " + formattedDate.toLocaleTimeString();
}

/**
 * Takes the string timestamp (HH:MM:ss) and returns an integer in minutes
 * @param timestamp 
 * @returns the minutes
 */
function timestampToNumber(timestamp) {
    return timestamp.substring(3,5);
}