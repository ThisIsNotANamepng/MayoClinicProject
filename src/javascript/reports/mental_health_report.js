fetch('../../php/trackers/tracker_controller.php?action=getMentalData')
        .then(res => res.json())
        .then(data => {
          const dates = data.map(entry => entry.logDate);
          const stressLevels = data.map(entry => entry.stressScore);
          const sleepDurations = data.map(entry => entry.sleepDuration);
          const moods = data.map(entry => entry.mood);
  
          // Stress Chart
          new Chart(document.getElementById('stressChart'), {
            type: 'line',
            data: {
              labels: dates,
              datasets: [{
                label: 'Stress Level (1-10)',
                data: stressLevels,
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: false,
                tension: 0.3
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true,
                  max: 10
                }
              }
            }
          });
  
          // Sleep Duration Chart
          new Chart(document.getElementById('sleepChart'), {
            type: 'bar',
            data: {
              labels: dates,
              datasets: [{
                label: 'Sleep Duration (hrs)',
                data: sleepDurations,
                backgroundColor: 'rgba(54, 162, 235, 0.6)'
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true
                }
              }
            }
          });
  
          // Mood Distribution Pie Chart
          const moodCounts = moods.reduce((acc, mood) => {
            acc[mood] = (acc[mood] || 0) + 1;
            return acc;
          }, {});
          const moodLabels = Object.keys(moodCounts);
          const moodData = Object.values(moodCounts);
  
          new Chart(document.getElementById('moodChart'), {
            type: 'pie',
            data: {
              labels: moodLabels,
              datasets: [{
                data: moodData,
                backgroundColor: [
                  '#FFD700', '#87CEFA', '#FF7F50', '#FF6347', '#8A2BE2', '#3CB371', '#A9A9A9'
                ]
              }]
            },
            options: {
              responsive: true
            }
          });
        })
        .catch(err => console.error('Error loading mental health data:', err));