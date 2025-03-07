<div>
    <x-navigation.bread-crumb icon='bi bi-bar-chart-fill' label="Paramètres">
        <x-navigation.bread-crumb-item label='Paramètres' />
    </x-navigation.bread-crumb>
    <div class="row">
        <div class="col-md-3">
            <div class="list-group settings-nav mb-4">
                <a href="#general" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                    <i class="bi bi-gear-fill me-2"></i> Paramètres généraux
                </a>
                <a href="#appearance" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="bi bi-palette me-2"></i> Apparence
                </a>
                <a href="#notifications" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="bi bi-bell me-2"></i> Notifications
                </a>
                <a href="#privacy" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="bi bi-shield-lock me-2"></i> Confidentialité et sécurité
                </a>
                <a href="#integration" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="bi bi-puzzle me-2"></i> Integrations
                </a>
                <a href="#backup" class="list-group-item list-group-item-action" data-bs-toggle="list">
                    <i class="bi bi-cloud-arrow-up me-2"></i> Backup & Restoration
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="tab-content">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">General Settings</h5>
                            <form id="generalSettingsForm">
                                <div class="mb-3">
                                    <label class="form-label">School Name</label>
                                    <input type="text" class="form-control" value="International School">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Admin Email</label>
                                    <input type="email" class="form-control" value="admin@school.com">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Time Zone</label>
                                    <select class="form-select">
                                        <option>UTC -08:00 Pacific Time</option>
                                        <option>UTC -05:00 Eastern Time</option>
                                        <option>UTC +00:00 Greenwich Mean Time</option>
                                        <option>UTC +01:00 Central European Time</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date Format</label>
                                    <select class="form-select">
                                        <option>MM/DD/YYYY</option>
                                        <option>DD/MM/YYYY</option>
                                        <option>YYYY-MM-DD</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Appearance Settings -->
                <div class="tab-pane fade" id="appearance">
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
                            <div class="mb-3">
                                <label class="form-label">Couleur primaire</label>
                                <input type="color" class="form-control form-control-color" value="#0d6efd">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Taille de la police</label>
                                <select class="form-select">
                                    <option>Petit</option>
                                    <option selected>Moyen</option>
                                    <option>Large</option>
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary">Appliquer</button>
                        </div>
                    </div>
                </div>

                <!-- Notifications Settings -->
                <div class="tab-pane fade" id="notifications">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Notification Preferences</h5>
                            <div class="mb-3">
                                <label class="form-label">Email Notifications</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">New Student Registration</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Fee Payment</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Attendance Reports</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Push Notifications</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">System Updates</label>
                                </div>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Important Announcements</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary">Save Preferences</button>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="tab-pane fade" id="privacy">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Privacy & Security Settings</h5>
                            <div class="mb-3">
                                <label class="form-label">Two-Factor Authentication</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">Enable 2FA</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Session Timeout</label>
                                <select class="form-select">
                                    <option>15 minutes</option>
                                    <option>30 minutes</option>
                                    <option selected>1 hour</option>
                                    <option>4 hours</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Data Sharing</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Share usage analytics</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary">Update Security Settings</button>
                        </div>
                    </div>
                </div>

                <!-- Integrations -->
                <div class="tab-pane fade" id="integration">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Integrated Services</h5>
                            <div class="list-group">
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Google Workspace</h6>
                                            <small class="text-muted">Connected</small>
                                        </div>
                                        <button class="btn btn-outline-danger btn-sm">Disconnect</button>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Microsoft Teams</h6>
                                            <small class="text-muted">Not connected</small>
                                        </div>
                                        <button class="btn btn-outline-primary btn-sm">Connect</button>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Payment Gateway</h6>
                                            <small class="text-muted">Connected</small>
                                        </div>
                                        <button class="btn btn-outline-danger btn-sm">Disconnect</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Backup & Restore -->
                <div class="tab-pane fade" id="backup">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Backup & Restore</h5>
                            <div class="mb-4">
                                <h6>Automatic Backup</h6>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" checked>
                                    <label class="form-check-label">Enable automatic backup</label>
                                </div>
                                <select class="form-select mt-2">
                                    <option>Daily</option>
                                    <option selected>Weekly</option>
                                    <option>Monthly</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <h6>Manual Backup</h6>
                                <button class="btn btn-primary">Create Backup Now</button>
                            </div>
                            <div class="mb-4">
                                <h6>Restore from Backup</h6>
                                <div class="input-group">
                                    <input type="file" class="form-control">
                                    <button class="btn btn-outline-secondary">Restore</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

                // Sidebar toggle
                const sidebarCollapse = document.getElementById('sidebarCollapse');
                const sidebar = document.getElementById('sidebar');
                const content = document.getElementById('content');

                if (sidebarCollapse && sidebar && content) {
                    sidebarCollapse.addEventListener('click', function() {
                        sidebar.classList.toggle('active');
                        content.classList.toggle('active');
                    });
                }

                // Form submissions
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        showSuccessToast('Settings saved successfully!');
                    });
                });

                // Initialize all tooltips
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });

                // Initialize Offcanvas
                const offcanvasLinks = document.querySelectorAll('[data-bs-toggle="offcanvas"]');
                offcanvasLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const target = this.getAttribute('data-bs-target');
                        const offcanvas = new bootstrap.Offcanvas(document.querySelector(target));
                        offcanvas.show();
                    });
                });

                // Handle Offcanvas form submissions
                const quickSettingsForm = document.getElementById('quickSettingsForm');
                if (quickSettingsForm) {
                    quickSettingsForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        showSuccessToast('Quick settings saved successfully!');
                        const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById(
                            'offcanvasRight'));
                        offcanvas.hide();
                    });
                }

                // Save settings on button clicks
                const saveButtons = document.querySelectorAll('.btn-primary');
                saveButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        showSuccessToast('Settings saved successfully!');
                    });
                });

                function showSuccessToast(message) {
                    const toastContainer = document.createElement('div');
                    toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                    toastContainer.innerHTML = `
      <div class="toast" role="alert">
        <div class="toast-header bg-success text-white">
          <strong class="me-auto">Success</strong>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
          ${message}
        </div>
      </div>
    `;
                    document.body.appendChild(toastContainer);

                    const toastElement = toastContainer.querySelector('.toast');
                    const toast = new bootstrap.Toast(toastElement);
                    toast.show();

                    // Remove toast container after toast is hidden
                    toastElement.addEventListener('hidden.bs.toast', function() {
                        toastContainer.remove();
                    });
                }
            });
        </script>
    @endpush
</div>
