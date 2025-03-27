<div>
    <div class="profile-header text-center">
        <livewire:application.admin.form.update-user-avatar-page />
        <h5 id="username" class="display-4">{{ Auth::user()->name }}</h5>
        <span>{{ Auth::user()->email }}</span><br>
        <span>{{ Auth::user()->phone }}</span><br>
        <span>{{ Auth::user()->role->name }}</span>
    </div>

    <div class="container mt-4"
        style=" background-color: white;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        padding: 2rem;">
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit"
                    type="button" role="tab" aria-controls="edit" aria-selected="false">
                    <i class="bi bi-pencil-square w-10"></i> Modifier infos
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password"
                    type="button" role="tab" aria-controls="password" aria-selected="false">
                    <i class="bi bi-key w-10"></i> Changer mot de passe
                </button>
            </li>
        </ul>
        <div class="tab-content mt-3" id="profileTabsContent">
            <div class="tab-pane fade show active" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <livewire:application.admin.form.edit-user-info-page />
            </div>
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <livewire:application.admin.form.update-password-page />
            </div>
        </div>
    </div>

</div>
