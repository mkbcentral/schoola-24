window.addEventListener('refresh-expenses', e => {
    const balances = e.detail.params;
    console.log(balances);
    const labels = [];
    const paymentsData = [];
    const expensesData = [];
    const balanceData = [];

    for (const month in balances) {
        for (const category in balances[month]) {
            labels.push(month);
            paymentsData.push(balances[month][category].payments);
            expensesData.push(balances[month][category].expenses);
            balanceData.push(balances[month][category].balance);
        }
    }

    const ctx = document.getElementById('expensesChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Recettes',
                    data: paymentsData,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Dépenses',
                    data: expensesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Solde',
                    data: balanceData,
                    backgroundColor: 'rgba(224, 30, 215, 0.2)', // Couleur verte
                    borderColor: 'rgba(75, 192, 75, 1)', // Couleur verte
                    borderWidth: 1
                }
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
