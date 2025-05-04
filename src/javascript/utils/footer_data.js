setUserDetails();

/**
 * Fetches session name and displays in footer
 */
function setUserDetails() {
    let welcomeMessage = document.getElementById('welcome-message');
    let footer = document.getElementById('footer');
    
    fetch('../../php/dashboard/dashboard.php?action=getName')
        .then((resp) => resp.text())
        .then((text) => { 
            // Capitalize name
            text = text.charAt(0).toUpperCase() + text.substring(1);
            footer.innerHTML = 'Current User: ' + text
        })
        .catch((err) => console.error('Server error: ' + err));
}