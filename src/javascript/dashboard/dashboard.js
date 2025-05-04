
setUserDetails();
loadActivityTable();
loadRemindersTable();

/**
 * Fetches session name and displays in welcome message and footer
 */
function setUserDetails() {
    let welcomeMessage = document.getElementById('welcome-message');
    let footer = document.getElementById('footer');
    
    fetch('../../php/dashboard/dashboard.php?action=getName')
        .then((resp) => resp.text())
        .then((text) => { 
            // Capitalize name
            text = text.charAt(0).toUpperCase() + text.substring(1);

            welcomeMessage.innerHTML = 'Welcome, ' + text + '!';
            footer.innerHTML = 'Current User: ' + text
        })
        .catch((err) => console.error('Server error: ' + err));
}

/**
 * Fetches activity data and creates html table with it
 */
function loadActivityTable() {
    let activityTableBody = document.querySelector('#activity-table > tbody');
    // Fetch activity
    fetch('../../php/dashboard/dashboard.php?action=getActivity&maxResults=3')
        .then(resp => resp.json())
        .then(json => {
            
            // Iterate over returned result array
            for (let x in json) {
                // create entry in html table for result
                let row = document.createElement('tr');
                row.className = 'table-entry';
                
                let timeCell = document.createElement('td');
                let descCell = document.createElement('td');
                
                timeCell.innerHTML = formatDateTime(json[x].activityTime);
                descCell.innerHTML = json[x].activityDescription;
                row.appendChild(timeCell);
                row.appendChild(descCell);


                // Append entry to table
                activityTableBody.appendChild(row);
            }
        })
        .catch(err => console.error(err));

}

/**
 * Fetches latest reminders data and creates html table with it
 */
function loadRemindersTable() {
    let reminderTable = document.getElementById('reminder-table');
    fetch('../../php/dashboard/dashboard.php?action=getReminders&maxResults=5')
        .then(resp => resp.json())
        .then(json => {
            for (let x in json) {
                if (x == 0) {
                    // Remove placeholder entry
                    let placeHolderEntry = document.getElementById('temp-reminder-entry');
                    placeHolderEntry.parentNode.removeChild(placeHolderEntry);
                }

                let row = document.createElement('tr');
                row.className = 'table-entry';
                
                let taskCell = document.createElement('td');
                let dueCell = document.createElement('td');
                taskCell.innerHTML = json[x].description;
                dueCell.innerHTML = formatDateTime(json[x].dueDate);
                row.appendChild(taskCell);
                row.appendChild(dueCell);

                // Append entry to table
                reminderTable.appendChild(row);
            }
        });
}