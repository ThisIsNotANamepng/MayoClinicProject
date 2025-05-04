// Fetch from server
fetch('../../php/trackers/tracker_controller.php?action=getFitnessData')
    .then(response => response.json())
    .then(data => {
        onDataRecieved(data);
    })
    .catch(error => console.error('Error fetching fitness data:', error));

/**
 * Handles recieved json array of data and updates tables
 */
function onDataRecieved(data) {
    
    let dates = [];
    let weights = [];
    let durations = [];
    let activityLevels = [];

    for (let x in data) {
        dates.push(data[x].logDate);
        weights.push(data[x].currWeight);
        durations.push(timestampToNumber(data[x].duration));
        activityLevels.push(data[x].category);
    }

    // Weight Chart
    new Chart(document.getElementById('weightChart'), {
        type: 'line',
        data: {
        labels: dates,
        datasets: [{
            label: 'Weight (lb)',
            data: weights,
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false,
            tension: 0.3
        }]
        },
        options: {
        responsive: true,
        scales: {
            y: { beginAtZero: false }
        }
        }
    });

    // Activity Duration Chart
    new Chart(document.getElementById('activityDurationChart'), {
        type: 'bar',
        data: {
        labels: dates,
        datasets: [{
            label: 'Activity Duration (min)',
            data: durations,
            backgroundColor: 'rgba(153, 102, 255, 0.6)'
        }]
        },
        options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
        }
    });

    // Activity Level Pie Chart
    const activityCounts = activityLevels.reduce((acc, level) => {
        acc[level] = (acc[level] || 0) + 1;
        return acc;
    }, {});
    const activityLabels = Object.keys(activityCounts);
    const activityData = Object.values(activityCounts);

    new Chart(document.getElementById('activityLevelChart'), {
        type: 'pie',
        data: {
        labels: activityLabels,
        datasets: [{
            label: 'Activity Level Count',
            data: activityData,
            backgroundColor: [
            '#FF6384',
            '#36A2EB',
            '#FFCE56',
            '#4BC0C0',
            '#9966FF'
            ]
        }]
        },
        options: {
        responsive: true
        }
    });
}