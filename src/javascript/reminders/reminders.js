function handleReminderSubmit(event) {
    event.preventDefault();
    var task = document.getElementById('task').value;
    var due = document.getElementById('due').value;
    if (task && due) {
      alert("New Reminder added: " + task + " at " + due);
      
      // Make request to backend to save reminder data to database
      fetch('../../php/reminders/reminders.php?action=postReminder', {method: 'POST', body: new FormData(reminderForm)})
        .then(resp => {
          if (!resp.ok) {
            alert("There was an error submitting the reminder, please try again later.");
          }
        })
        .catch(err => console.error(err));


      var tr = document.createElement('tr');
      
      var tdTask = document.createElement('td');
      tdTask.textContent = task;
      tr.appendChild(tdTask);
      
      var tdDue = document.createElement('td');
      console.log(String.prototype.replace("T", " ", due));
      tdDue.textContent = due.replace("T", " ");
      tr.appendChild(tdDue);
      
      
      document.getElementById('reminder-table-body').appendChild(tr);
      
     
      document.getElementById('task').value = "";
      document.getElementById('due').value = "";
    } else {
      alert("Please fill in both fields.");
    }
  }

  function loadReminderTable() {
      fetch('../../php/reminders/reminders.php?action=getReminders')
        .then(resp => resp.json())
        .then(json => {

          for (let x in json) {
            var tr = document.createElement('tr');
        
            var tdTask = document.createElement('td');
            tdTask.textContent = json[x].description;
            tr.appendChild(tdTask);
            
            var tdDue = document.createElement('td');
            tdDue.textContent = formatDateTime(json[x].dueDate);
            tr.appendChild(tdDue);
            document.getElementById('reminder-table-body').appendChild(tr);
          }
        });
  }

  var reminderForm = document.getElementById('new-reminder-form');
  reminderForm.addEventListener('submit', handleReminderSubmit, false);

  loadReminderTable();