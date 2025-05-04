const form = document.getElementById('mealForm')
form.addEventListener('submit', (event) => submitData(event));

function submitData(event) {
    let feedback =  document.getElementById('feedback');
    event.preventDefault();

    // Post data to server
    fetch('../../php/trackers/tracker_controller.php?action=postMealData', {method: 'POST', body: new FormData(form)})
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
    document.getElementById('mealType').value="";
    document.getElementById('mealDate').value="";
    document.getElementById('title').value="";
    document.getElementById('calories').value="";
    document.getElementById('protein').value="";
    document.getElementById('carbs').value="";
    document.getElementById('fats').value="";
    document.getElementById('notes').value="";
}