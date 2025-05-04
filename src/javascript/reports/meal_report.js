fetch('../../php/trackers/tracker_controller.php?action=getMealData')
    .then(res => res.json())
    .then(data => {
        onDataRecieved(data);
    })
    .catch(err => console.error('Error fetching meal data:', err));

function onDataRecieved(data) {
    const dates = data.map(entry => entry.logDate);
    const calories = data.map(entry => entry.calories);
    const proteins = data.map(entry => entry.protein);
    const carbs = data.map(entry => entry.carbs);
    const fats = data.map(entry => entry.fats);
    const mealTypes = data.map(entry => entry.mealType);

    // Calorie Line Chart
    new Chart(document.getElementById('calorieChart'), {
      type: 'line',
      data: {
        labels: dates,
        datasets: [{
          label: 'Calories (kcal)',
          data: calories,
          borderColor: 'rgba(255, 159, 64, 1)',
          backgroundColor: 'rgba(255, 159, 64, 0.2)',
          fill: true,
          tension: 0.3
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

    // Macronutrient Totals
    const totalProtein = proteins.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);
    const totalCarbs = carbs.reduce((a, b) =>parseInt(a, 10) + parseInt(b, 10), 0);
    const totalFats = fats.reduce((a, b) => parseInt(a, 10) + parseInt(b, 10), 0);

    new Chart(document.getElementById('nutrientChart'), {
      type: 'bar',
      data: {
        labels: ['Protein (g)', 'Carbs (g)', 'Fats (g)'],
        datasets: [{
          label: 'Total Intake',
          data: [totalProtein, totalCarbs, totalFats],
          backgroundColor: [
            'rgba(75, 192, 192, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(255, 99, 132, 0.6)'
          ]
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // Meal Type Distribution
    const typeCounts = mealTypes.reduce((acc, type) => {
      acc[type] = (acc[type] || 0) + 1;
      return acc;
    }, {});
    const mealTypeLabels = Object.keys(typeCounts);
    const mealTypeData = Object.values(typeCounts);

    new Chart(document.getElementById('mealTypeChart'), {
      type: 'doughnut',
      data: {
        labels: mealTypeLabels,
        datasets: [{
          data: mealTypeData,
          backgroundColor: [
            '#36A2EB', '#FFCE56', '#FF6384', '#8E44AD'
          ]
        }]
      },
      options: {
        responsive: true
      }
    });
}