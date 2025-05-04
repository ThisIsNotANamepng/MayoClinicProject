const settingsForm = document.getElementById('account-settings-form');
const logoutBtn = document.getElementById('logout-button');

loadUserSettingsAndDetails();

settingsForm.addEventListener('submit', (event) => saveUserSettings(event));
logoutBtn.addEventListener('click', logout);

document.getElementById('discard-button').addEventListener('click', loadUserSettings);

/**
 * Fetches user details from database to populate account settings page
 */
function loadUserSettingsAndDetails() {
    // Load details section
    fetch('../../php/account/account.php?action=getDetails')
        .then((resp) => resp.json())
        .then((json) => populateDetailsElements(json))
        .catch((err) => 'Server error: ' + err);

    loadUserSettings();
}

function loadUserSettings() {
     // Load settings section
     fetch('../../php/account/account.php?action=getSettings')
        .then((resp) => resp.json())
        .then((json) => populateSettingsElements(json))
        .catch((err) => 'Server error: ' + err);
}

function populateDetailsElements(json) {
    let id = json.id;
    let email = json.email;
    
    document.getElementById('idDisplay').innerHTML = id;
    document.getElementById('emailDisplay').innerHTML = email;
}

function populateSettingsElements(json) {
    document.getElementById('input-fname').value = json.firstName;
    document.getElementById('input-lname').value = json.lastName;

    if (json.dob) {
        document.getElementById('input-dob').value = json.dob;
    }

    document.getElementById('input-height-feet').value = json.heightFeet;
    document.getElementById('input-height-inches').value = json.heightInches;

    if (json.startWeight) {
        document.getElementById('input-weight-pounds').value = json.startWeight;
    }

    if (json.allergies) {
        document.getElementById('input-allergies').value = json.allergies;
    }

    if (json.conditions){
        document.getElementById('input-conditions').value = json.conditions;
    }

    if (json.medicalHistory) {
        document.getElementById('input-history').value = json.medicalHistory;
    }
}

/**
 * Takes input user details and saves to userdetails table in mysql
 */
function saveUserSettings(event) {
    event.preventDefault();

    fetch('../../php/account/account.php', { method: 'POST', body: new FormData(settingsForm)})
        .then(resp => {
            if (resp.ok) {
                document.getElementById('response-message').innerHTML = "Changes saved successfully!"
            } else {
                document.getElementById('response-message').innerHTML = "An error has occurred, please try again later."
            }
        })
        .catch(err => console.error("Error while saving settings: " + err));
}

/**
 * Logs the user out
 */
function logout() {
    fetch('../../php/account/account.php?action=logout')
        .then(resp => {
            if (resp.ok) {
                window.location.href = "/src/html/index.html";
            }
        });
}