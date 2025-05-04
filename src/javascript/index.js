const LOG_IN = 'Log In';
const SIGN_UP = 'Sign Up';

let contents = document.getElementById('contents');

// success / error messaging to user
let message = document.getElementById('message');

let form = document.createElement('form');

function displayForm(status) {

    if (status === SIGN_UP) {
        document.getElementById('mayo-logo').style = "max-width : 100px;"
    }

    message.hidden = false;

    // Create/configure new elements to be added
    form.id = 'input-form';
    form.method = 'post';
    form.addEventListener('submit', (event) => onFormSubmit(event, status));
    let emailInput = createInput('email', true ,'Email', 'email');
    let passInput = createInput('password', true, 'Password', 'password');

    // Additional elements for sign up
    if (status === SIGN_UP) {
        var nameInput = createInput('text', true, 'First Name', 'fName');
        var lastNameInput = createInput('text', true, 'Last Name', 'lName');

        // Non-Required : can be configured in account page
        var heightFeetInput = createInput('number', false, 'Height (Feet)', 'hFeet');
        var heightInchesInput = createInput('number', false, 'Height (Inches)', 'hInch');
        var weightInput = createInput('number', false, 'Weight (lbs)', 'weight');

        // Add constraints
        heightFeetInput.min = 0
        heightFeetInput.max = 7;
        heightInchesInput.min = 0;
        heightInchesInput.max = 11;
        weightInput.min = 0;
    }

    let submitBtn = document.createElement('input');
    submitBtn.type = 'submit';
    submitBtn.value = status;
    submitBtn.className = 'form-button';
    let backLink = document.createElement('button');
    backLink.innerHTML = 'Go Back';
    backLink.className = 'form-button';
    backLink.onclick = function() {location.reload();};

    //Update status text
    document.getElementById('status').innerHTML = status;

    // Remove button prompts to login / signup
    document.querySelectorAll('.primary-button').forEach( element => {
        contents.removeChild(element);
    });

    // Append new elements
    contents.appendChild(form);
    form.appendChild(emailInput);
    form.appendChild(passInput);
    if (status === SIGN_UP) {
        form.appendChild(nameInput);
        form.appendChild(lastNameInput);
        form.appendChild(heightFeetInput);
        form.appendChild(heightInchesInput);
        form.appendChild(weightInput);  
    }
    form.appendChild(submitBtn);
    contents.appendChild(backLink);
}

/** 
 * Creates an input element with the specified attributes,
 * automatically is assigned the form-input class for styling
 * 
 * @param type - The input type
 * @param required - Input required flag
 * @param placeholder - The input placeholder text
 * @param name - The name (to be used when referenced in php)
 */
function createInput(type, required, placeHolder, name) {
    if (typeof type !== 'string' || typeof required !== 'boolean') {
        console.error('Bad params passed into createInput! [ Type = ' + type + " Required = " + required);
        return;
    }

    let input = document.createElement('input');
    input.type = type;
    input.required = required;
    input.className = 'form-input';
    input.placeholder = placeHolder;
    input.name = name;

    if (required) {
        input.placeholder += ' (Required)';
    }
    
    return input;
}

function onFormSubmit(event, status) {
    // prevent page reload
    event.preventDefault();

    //determine sign up or login
    let action;
    if (status == LOG_IN) {
        action = 'login';
    } else if (status == SIGN_UP) {
        action = 'signup';
    }

    // make and handle request to server
    fetch('../php/index.php?action='+action, { method: 'POST', body: new FormData(form)})
        .then(resp => handleResponse(resp))
        .catch(error => console.error('Server error: ' + error));
}

function handleResponse(resp) {
    
    // Logging and user response
    resp.text()
        .then(body => {
            console.log('Server response:' + body)
            message.innerHTML = body;

            if (resp.ok) {
                console.log('Redirecting User...');
                window.location.href = './dashboard/dashboard.html';
            }
        });
}