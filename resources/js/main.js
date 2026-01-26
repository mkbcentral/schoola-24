document.addEventListener('DOMContentLoaded', function () {
    // Charts theme is now managed by the global Alpine store

    // Apply theme to charts based on current theme
    function getChartTheme() {
        const isDark = document.documentElement.classList.contains('dark');
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



    // Sidebar toggle avec mode compact (desktop) et show/hide (mobile)
    const initSidebarToggle = () => {
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        if (!sidebarCollapse || !sidebar || !content) {
            console.warn('Sidebar elements not found:', { sidebarCollapse, sidebar, content });
            return;
        }

        console.log('Sidebar toggle initialized');

        // Fonction pour vérifier si on est sur mobile
        const isMobile = () => window.innerWidth < 768;

        // Initialisation au chargement
        const initSidebar = () => {
            if (!isMobile()) {
                // Desktop: Restaurer l'état du sidebar depuis localStorage
                const sidebarState = localStorage.getItem('sidebarState');
                if (sidebarState === 'compact') {
                    sidebar.classList.add('active');
                    content.classList.add('active');
                } else {
                    sidebar.classList.remove('active');
                    content.classList.remove('active');
                }
                // S'assurer que show est retiré
                sidebar.classList.remove('show');
            } else {
                // Mobile: S'assurer que le sidebar est caché par défaut
                sidebar.classList.remove('show', 'active');
                content.classList.remove('active');
            }
        };

        // Initialiser au chargement
        initSidebar();

        // Toggle handler avec prévention de double-clic
        let isToggling = false;
        sidebarCollapse.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('Toggle clicked, isMobile:', isMobile(), 'isToggling:', isToggling);

            if (isToggling) return;
            isToggling = true;

            if (isMobile()) {
                // Sur mobile: show/hide le sidebar
                const willShow = !sidebar.classList.contains('show');
                sidebar.classList.toggle('show');
                console.log('Mobile toggle - sidebar.show:', willShow);
            } else {
                // Sur desktop: compact/expanded
                sidebar.classList.toggle('active');
                content.classList.toggle('active');

                // Sauvegarder l'état (uniquement desktop)
                const isCompact = sidebar.classList.contains('active');
                localStorage.setItem('sidebarState', isCompact ? 'compact' : 'expanded');
                console.log('Desktop toggle - compact:', isCompact);
            }

            // Réactiver après l'animation
            setTimeout(() => {
                isToggling = false;
            }, 400);
        });

        // Fermer le sidebar sur mobile en cliquant sur l'overlay ou en dehors
        document.addEventListener('click', function(e) {
            if (isMobile() && sidebar.classList.contains('show')) {
                // Ne pas fermer si on clique sur le bouton toggle
                if (e.target.closest('#sidebarCollapse')) {
                    return;
                }

                // Fermer si on clique en dehors du sidebar
                if (!e.target.closest('.sidebar-modern')) {
                    sidebar.classList.remove('show');
                    console.log('Sidebar closed by outside click');
                }
            }
        });

        // Fermer le sidebar sur mobile avec la touche Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isMobile() && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                console.log('Sidebar closed by Escape key');
            }
        });

        // Gérer le redimensionnement de la fenêtre
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(initSidebar, 250);
        });
    };

    // Appeler l'initialisation
    initSidebarToggle();

    // Réessayer après un court délai si les éléments ne sont pas trouvés
    setTimeout(() => {
        if (!document.getElementById('sidebar') || !document.getElementById('sidebarCollapse')) {
            console.log('Retrying sidebar initialization...');
            initSidebarToggle();
        }
    }, 500);




});

// Add smooth scrolling to anchor links only (exclude dropdowns and other interactive elements)
document.querySelectorAll('a[href^="#"]:not([data-bs-toggle]):not([role="button"])').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        // Only handle real anchor links, not empty # or dropdown triggers
        if (href && href !== "#" && href !== "#!" && !href.includes('dropdown')) {
            const element = document.querySelector(href);
            if (element) {
                e.preventDefault();
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

