// Assurez-vous que Chart.js est importé avant ce script, par exemple :
// import Chart from 'chart.js/auto'; // Si vous utilisez un bundler
// ou incluez <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> dans votre HTML

window.myExpensesChart = window.myExpensesChart || null;

window.addEventListener('refresh-expenses', e => {
    const balances = e.detail.params;
    console.log('Balances data received:', balances);
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

    const ctx = document.getElementById('expensesChart')?.getContext('2d');
    if (!ctx) {
        console.warn('Element #expensesChart non trouvé dans le DOM.');
        return;
    }

    // Détruire l'ancien graphique s'il existe
    if (window.myExpensesChart && typeof window.myExpensesChart.destroy === 'function') {
        window.myExpensesChart.destroy();
        window.myExpensesChart = null;
    }

    // Crée le graphique
    window.myExpensesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Recettes',
                    data: paymentsData,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(75, 192, 192, 0.9)',
                    hoverBorderColor: 'rgba(75, 192, 192, 1)'
                },
                {
                    label: 'Dépenses',
                    data: expensesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(255, 99, 132, 0.9)',
                    hoverBorderColor: 'rgba(255, 99, 132, 1)'
                },
                {
                    label: 'Solde',
                    data: balanceData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    borderRadius: 8,
                    hoverBackgroundColor: 'rgba(54, 162, 235, 0.9)',
                    hoverBorderColor: 'rgba(54, 162, 235, 1)'
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Évolution des Recettes, Dépenses et Solde',
                    font: {
                        size: 20,
                        weight: 'bold'
                    },
                    color: '#333'
                },
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        font: {
                            size: 14
                        }
                    }
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(0,0,0,0.8)',
                    titleFont: { size: 16 },
                    bodyFont: { size: 14 }
                }
            },
            layout: {
                padding: 20
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(200,200,200,0.2)'
                    }
                }
            },
            backgroundColor: '#fafafa',
            elements: {
                bar: {
                    borderWidth: 2,
                    borderRadius: 8
                }
            },
            barPercentage: 0.95,
            categoryPercentage: 0.95
        }
    });
});

window.addEventListener('refresh-budget-forecast', e => {
    // e.detail.params should be an array of objects like:
    const data = e.detail.params;

    const tableauCategories = Object.values(data).map(item => ({
        category_name: item.category_name.trim().toUpperCase(), // MAJUSCULE
        total_amount: item.total_amount
    }));
    console.log('Tableau des catégories:', tableauCategories);

    // Préparer les labels et les données
    const labels = tableauCategories.map(item => item.category_name);
    const totalAmounts = tableauCategories.map(item => item.total_amount);

    // Générer des couleurs pastel pour chaque catégorie
    const backgroundColors = labels.map((_, i) =>
        `hsl(${(i * 360 / labels.length)}, 70%, 60%)`
    );
    const borderColors = labels.map((_, i) =>
        `hsl(${(i * 360 / labels.length)}, 70%, 45%)`
    );

    // Créer ou mettre à jour le graphique
    let forecastChart = window.forecastChart || null;
    const ctx = document.getElementById('budgetForecastChart').getContext('2d');

    if (forecastChart && typeof forecastChart.destroy === 'function') {
        forecastChart.destroy();
        forecastChart = null;
    }

    forecastChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'MONTANT TOTAL PAR CATÉGORIE',
                    data: totalAmounts,
                    fill: false,
                    borderColor: backgroundColors[0] || '#4B3869',
                    backgroundColor: backgroundColors[0] || '#4B3869',
                    pointBackgroundColor: backgroundColors,
                    pointBorderColor: borderColors,
                    pointRadius: 9,
                    pointHoverRadius: 15,
                    borderWidth: 5,
                    tension: 0.4,
                    hoverBorderWidth: 6,
                    shadowOffsetX: 2,
                    shadowOffsetY: 2,
                    shadowBlur: 8,
                    shadowColor: 'rgba(75,56,105,0.15)'
                }
            ]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'PRÉVISION DU BUDGET PAR CATÉGORIE',
                    font: {
                        size: 26,
                        weight: 'bold',
                        family: "'Segoe UI', 'Arial', sans-serif"
                    },
                    color: '#4B3869',
                    padding: { top: 20, bottom: 35 }
                },
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(75,56,105,0.95)',
                    titleFont: { size: 19, weight: 'bold' },
                    bodyFont: { size: 16 },
                    borderColor: '#4B3869',
                    borderWidth: 2,
                    caretSize: 10,
                    padding: 16,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed.y !== undefined ? context.parsed.y : context.parsed;
                            return `${context.label}: ${value.toLocaleString()} $`;
                        }
                    }
                }
            },
            layout: {
                padding: { left: 40, right: 40, top: 30, bottom: 30 }
            },
            backgroundColor: '#f3f0fa',
            animation: {
                animateRotate: true,
                animateScale: true,
                duration: 1200,
                easing: 'easeOutQuart'
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'CATÉGORIE',
                        color: '#1976D2',
                        font: { size: 20, weight: 'bold' }
                    },
                    ticks: { color: '#4B3869', font: { size: 17, weight: 'bold' }, padding: 10 },
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'MONTANT ($)',
                        color: '#C62828',
                        font: { size: 20, weight: 'bold' }
                    },
                    ticks: { color: '#4B3869', font: { size: 16 }, padding: 8 },
                    grid: { color: 'rgba(200,200,200,0.13)', borderDash: [4, 4] }
                }
            },
            elements: {
                line: {
                    borderWidth: 5,
                    borderJoinStyle: 'round'
                },
                point: {
                    radius: 9,
                    hoverRadius: 15,
                    backgroundColor: undefined,
                    borderColor: '#fff',
                    borderWidth: 3,
                    shadowBlur: 8,
                    shadowColor: 'rgba(75,56,105,0.15)'
                }
            }
        }
    });
    window.forecastChart = forecastChart;
});


window.addEventListener('refresh-budget-forecast-monthly', e => {
    // e.detail.params should be an array of objects like:
    const data = e.detail.params;
    const tableauComplet = Object.values(data).map(item => ({
        category_name: item.category_name.trim().toUpperCase(), // MAJUSCULE
        monthly_amounts: item.monthly_amounts,
        total_amount: item.total_amount
    }));
    console.log('Tableau complet des mouvements mensuels:', tableauComplet);

    // Map des numéros de mois vers noms français
    const monthNames = {
        '01': 'Janvier', '02': 'Février', '03': 'Mars', '04': 'Avril',
        '05': 'Mai', '06': 'Juin', '07': 'Juillet', '08': 'Août',
        '09': 'Septembre', '10': 'Octobre', '11': 'Novembre', '12': 'Décembre'
    };

    // Récupérer tous les mois présents dans les données
    const allMonthsSet = new Set();
    tableauComplet.forEach(item => {
        Object.keys(item.monthly_amounts).forEach(month => allMonthsSet.add(month));
    });

    // Trier les mois par numéro croissant si possible
    const allMonths = Array.from(allMonthsSet).sort((a, b) => a.localeCompare(b, undefined, { numeric: true }));

    // Générer les labels formatés seulement avec le nom du mois en MAJUSCULE
    const monthLabels = allMonths.map(m => {
        let num = m.padStart(2, '0');
        return (monthNames[num] || m).toUpperCase();
    });

    // Préparer les datasets pour chaque catégorie
    const datasets = tableauComplet.map(item => {
        const color = `hsl(${Math.floor(Math.random() * 360)}, 70%, 60%)`;
        return {
            label: item.category_name, // Déjà en MAJUSCULE
            data: allMonths.map(month => item.monthly_amounts[month] || 0),
            fill: false,
            borderWidth: 3,
            borderColor: color,
            backgroundColor: color,
            pointBackgroundColor: color,
            pointBorderColor: '#fff',
            pointRadius: 6,
            pointHoverRadius: 10,
            tension: 0.35,
            hoverBorderWidth: 4
        };
    });

    // Créer ou mettre à jour le graphique
    const ctxMovements = document.getElementById('monthlyMovementsChart')?.getContext('2d');
    if (ctxMovements) {
        // Détruire l'ancien graphique s'il existe
        if (window.monthlyMovementsChart && typeof window.monthlyMovementsChart.destroy === 'function') {
            window.monthlyMovementsChart.destroy();
        }

        window.monthlyMovementsChart = new Chart(ctxMovements, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: datasets
            },
            options: {
                plugins: {
                    title: {
                        display: true,
                        text: 'MOUVEMENTS MENSUELS PAR CATÉGORIE',
                        font: { size: 22, weight: 'bold', family: "'Segoe UI', 'Arial', sans-serif" },
                        color: '#4B3869',
                        padding: { top: 10, bottom: 20 }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 15, weight: 'bold' },
                            color: '#333',
                            padding: 18,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0,0,0,0.85)',
                        titleFont: { size: 16, weight: 'bold' },
                        bodyFont: { size: 14 },
                        borderColor: '#4B3869',
                        borderWidth: 2,
                        caretSize: 8,
                        padding: 12
                    }
                },
                layout: {
                    padding: { left: 30, right: 30, top: 20, bottom: 20 }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'MOIS',
                            color: '#1976D2', // Couleur bleue
                            font: { size: 20, weight: 'bold', family: "'Segoe UI', 'Arial', sans-serif" },
                            padding: {top: 10}
                        },
                        grid: { display: false },
                        ticks: { color: '#4B3869', font: { size: 16, weight: 'bold', family: "'Segoe UI', 'Arial', sans-serif" } }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'MONTANT',
                            color: '#C62828', // Couleur rouge
                            font: { size: 20, weight: 'bold', family: "'Segoe UI', 'Arial', sans-serif" },
                            padding: {right: 10}
                        },
                        grid: { color: 'rgba(200,200,200,0.13)', borderDash: [4, 4] },
                        ticks: { color: '#4B3869', font: { size: 15 } }
                    }
                },
                backgroundColor: '#fafafa',
                elements: {
                    line: {
                        borderWidth: 3
                    },
                    point: {
                        radius: 6,
                        hoverRadius: 10,
                        backgroundColor: undefined, // Use dataset color
                        borderColor: '#fff',
                        borderWidth: 2
                    }
                }
            }
        });
    } else {
        console.warn('Element #monthlyMovementsChart non trouvé dans le DOM.');
    }
});





