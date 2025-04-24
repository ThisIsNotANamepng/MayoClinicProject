function handleReminderSubmit(event) {
    event.preventDefault();
    var task = document.getElementById('task').value;
    var due = document.getElementById('due').value;
    if (task && due) {
      alert("New Reminder added: " + task + " at " + due);
      
     
      var tr = document.createElement('tr');
      
      var tdTask = document.createElement('td');
      tdTask.textContent = task;
      tr.appendChild(tdTask);
      
      var tdDue = document.createElement('td');
      tdDue.textContent = due;
      tr.appendChild(tdDue);
      
      
      document.getElementById('reminder-table-body').appendChild(tr);
      
     
      document.getElementById('task').value = "";
      document.getElementById('due').value = "";
    } else {
      alert("Please fill in both fields.");
    }
  }

  var reminderForm = document.getElementById('new-reminder-form');
  reminderForm.addEventListener('submit', handleReminderSubmit, false);