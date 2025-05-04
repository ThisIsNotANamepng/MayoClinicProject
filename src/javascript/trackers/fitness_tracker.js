const form = document.getElementById('fitnessForm')
form.addEventListener('submit', (event) => submitData(event));

function submitData(event) {
    let feedback =  document.getElementById('feedback');
    event.preventDefault();

    // Post data to server
    fetch('../../php/trackers/tracker_controller.php?action=postFitnessData', {method: 'POST', body: new FormData(form)})
        .then(resp => {
            if (resp.ok) {
                feedback.style = "color: green";
            } else {
                feedback.style = "color: red";
            }
            return resp.text()
        })
        .then(text => {
            document.getElementById('feedback').innerHTML = text;
            resetForm();
        });
}   

/**
 * Clears form details
 */
function resetForm() {
    document.getElementById('date').value="";
    document.getElementById('length').value="";
    document.getElementById('activity').value="";
    document.getElementById('weight').value="";
}