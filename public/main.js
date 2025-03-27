document.addEventListener('DOMContentLoaded', function () {    


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

    // Handle table row dropdown states
    const dropdowns = document.querySelectorAll('.table .dropdown');

    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('show.bs.dropdown', function () {
            // Add class to parent row when dropdown opens
            this.closest('tr').classList.add('dropdown-open');
        });

        dropdown.addEventListener('hide.bs.dropdown', function () {
            // Remove class from parent row when dropdown closes
            this.closest('tr').classList.remove('dropdown-open');
        });
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

    // Initialize all tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize all popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
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