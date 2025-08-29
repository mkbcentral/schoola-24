document.addEventListener('DOMContentLoaded', function () {
    // Theme initialization
    const savedTheme = localStorage.getItem('theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

    let initialTheme = savedTheme;
    if (!savedTheme || savedTheme === 'auto') {
        initialTheme = prefersDark ? 'dark' : 'light';
    }

    document.documentElement.setAttribute('data-bs-theme', initialTheme);

    // Theme management
    const themeSwitch = document.querySelector('.theme-switch');
    const currentThemeSpan = document.querySelector('.current-theme');

    if (themeSwitch && currentThemeSpan) {
        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme') || 'light';
        currentThemeSpan.textContent = capitalizeFirstLetter(initialTheme);

        // Theme switch handler
        themeSwitch.addEventListener('click', function (e) {
            e.preventDefault();
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';

            document.documentElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            currentThemeSpan.textContent = capitalizeFirstLetter(newTheme);
        });
    }

    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    // Apply theme to charts based on current theme
    function getChartTheme() {
        const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
        return {
            color: isDark ? '#fff' : '#666',
            gridColor: isDark ? '#373b3e' : '#ddd',
            backgroundColor: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)'
        };
    }

    function createChartConfig(type, labels, datasets, options = {}) {
        const theme = getChartTheme();
        return {
            type: type,
            data: {
                labels: labels,
                datasets: datasets.map(dataset => ({
                    ...dataset,
                    borderColor: dataset.borderColor || theme.color,
                    color: theme.color
                }))
            },
            options: {
                ...options,
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: theme.color
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: theme.gridColor
                        },
                        ticks: {
                            color: theme.color
                        }
                    },
                    y: {
                        grid: {
                            color: theme.gridColor
                        },
                        ticks: {
                            color: theme.color
                        }
                    }
                }
            }
        };
    }

    // Initialize charts with theme support
    const charts = {
        attendance: document.getElementById('attendanceChart'),
        finance: document.getElementById('financeChart'),
        gradeDistribution: document.getElementById('gradeDistributionChart'),
        performance: document.getElementById('performanceChart'),
        monthlyFee: document.getElementById('monthlyFeeChart'),
        feeDistribution: document.getElementById('feeDistributionChart'),
        feeTypes: document.getElementById('feeTypesChart')
    };


    // Update charts when theme changes
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.attributeName === 'data-bs-theme') {
                Chart.helpers.each(Chart.instances, function (instance) {
                    const theme = getChartTheme();
                    instance.options.plugins.legend.labels.color = theme.color;
                    instance.options.scales.x.grid.color = theme.gridColor;
                    instance.options.scales.y.grid.color = theme.gridColor;
                    instance.options.scales.x.ticks.color = theme.color;
                    instance.options.scales.y.ticks.color = theme.color;
                    instance.update();
                });
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-bs-theme']
    });



    // Sidebar toggle
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');

    if (sidebarCollapse && sidebar && content) {
        sidebarCollapse.addEventListener('click', function () {
            sidebar.classList.toggle('active');
            content.classList.toggle('active');
        });
    }




});

// Add smooth scrolling to all links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const href = this.getAttribute('href');
        if (href !== "#") {
            const element = document.querySelector(href);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }
    });
});

  window.addEventListener('open-form-payment', e => {
            new bootstrap.Modal(document.getElementById('form-payment')).show();
  });

