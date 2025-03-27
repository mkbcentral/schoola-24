<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Paramètres d'apparence</h5>
            <div class="mb-4">
                <label class="form-label d-block">Theme</label>
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="theme" id="lightTheme">
                    <label class="btn btn-outline-primary" for="lightTheme">
                        <i class="bi bi-sun-fill me-2"></i>Clair
                    </label>
                    <input type="radio" class="btn-check" name="theme" id="darkTheme">
                    <label class="btn btn-outline-primary" for="darkTheme">
                        <i class="bi bi-moon-stars-fill me-2"></i>Sombre
                    </label>
                    <input type="radio" class="btn-check" name="theme" id="autoTheme">
                    <label class="btn btn-outline-primary" for="autoTheme">
                        <i class="bi bi-circle-half me-2"></i>Auto
                    </label>
                </div>
                <small class="form-text text-muted d-block mt-2">
                    Le thème automatique suit vos préférences système
                </small>
            </div>
        </div>
    </div>
    @push('js')
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                // Theme management
                const lightTheme = document.getElementById('lightTheme');
                const darkTheme = document.getElementById('darkTheme');
                const autoTheme = document.getElementById('autoTheme');

                function setTheme(theme) {
                    document.documentElement.setAttribute('data-bs-theme', theme);
                    localStorage.setItem('theme', theme);

                    // Update Chart.js instances for dark mode
                    if (window.Chart) {
                        Chart.helpers.each(Chart.instances, function(instance) {
                            const isDark = theme === 'dark';
                            instance.options.plugins.legend.labels.color = isDark ? '#fff' : '#666';
                            instance.options.scales.x.grid.color = isDark ? '#373b3e' : '#ddd';
                            instance.options.scales.y.grid.color = isDark ? '#373b3e' : '#ddd';
                            instance.options.scales.x.ticks.color = isDark ? '#fff' : '#666';
                            instance.options.scales.y.ticks.color = isDark ? '#fff' : '#666';
                            instance.update();
                        });
                    }
                }

                // Check saved theme or system preference
                if (lightTheme && darkTheme && autoTheme) {
                    const savedTheme = localStorage.getItem('theme');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

                    let initialTheme = savedTheme;
                    if (!savedTheme || savedTheme === 'auto') {
                        initialTheme = prefersDark ? 'dark' : 'light';
                        autoTheme.checked = true;
                    } else {
                        if (savedTheme === 'light') {
                            lightTheme.checked = true;
                        } else {
                            darkTheme.checked = true;
                        }
                    }

                    setTheme(initialTheme);

                    // Theme change handlers
                    lightTheme.addEventListener('change', () => {
                        if (lightTheme.checked) {
                            setTheme('light');
                        }
                    });

                    darkTheme.addEventListener('change', () => {
                        if (darkTheme.checked) {
                            setTheme('dark');
                        }
                    });

                    autoTheme.addEventListener('change', () => {
                        if (autoTheme.checked) {
                            const theme = prefersDark ? 'dark' : 'light';
                            setTheme(theme);
                            localStorage.setItem('theme', 'auto');
                        }
                    });

                    // Listen for system theme changes
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
                        if (autoTheme.checked) {
                            setTheme(e.matches ? 'dark' : 'light');
                        }
                    });
                }


            });
        </script>
    @endpush
</div>
