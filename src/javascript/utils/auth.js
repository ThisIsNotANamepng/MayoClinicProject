// TODO: add this script to all pages
isLoggedIn();

// Makes a request to backend to determine if the access is being made by a logged in user
function isLoggedIn() {
    fetch('../../php/utils/auth_util.php')
        .then(resp => {
            if (!resp.ok) {
                // Not logged in, redirect to login
                window.location.href = '/src/html/index.html';
            }
        })
}