<div>
    <div class="profile-header text-center">
        <img src="{{ asset('images/defautl-user.jpg') }}" alt="User avatar" class="avatar mb-4">
        <h3 id="username" class="display-4">{{ Auth::user()->name }}</h3>
    </div>

    <div class="container mt-4"
        style=" background-color: white;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        padding: 2rem;">
        <ul class="nav nav-tabs" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                    type="button" role="tab" aria-controls="info" aria-selected="true">
                    <i class="bi bi-person w-10"></i> Infos de l'utilisateur
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="edit-tab" data-bs-toggle="tab" data-bs-target="#edit" type="button"
                    role="tab" aria-controls="edit" aria-selected="false">
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
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <h3 class="mb-4">User Information</h3>
                <p>
                    <strong><i class="bi bi-person-fill"></i> Nom:</strong>
                    <span id="userEmail">{{ Auth::user()->name }}</span>
                </p>
                <p>
                    <strong><i class="bi bi-envelope-at-fill"></i> Email:</strong>
                    <span id="userEmail">{{ Auth::user()->email }}</span>
                </p>
                <p>
                    <strong><i class="bi bi-person-fill-lock"></i> Role:</strong>
                    <span id="userLocation">{{ Auth::user()->role->name }}</span>
                </p>
            </div>
            <div class="tab-pane fade" id="edit" role="tabpanel" aria-labelledby="edit-tab">
                <h3 class="mb-4">Edit Profile</h3>
                <form id="editProfileForm">
                    <div class="mb-3">
                        <label for="editUsername" class="form-label">Username</label>
                        <input type="text" class="form-control" id="editUsername" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="editLocation">
                    </div>
                    <div class="mb-3">
                        <label for="editBio" class="form-label">Bio</label>
                        <textarea class="form-control" id="editBio" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-2"></i> Update
                        Profile</button>
                </form>
            </div>
            <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                <h3 class="mb-4">Change Password</h3>
                <form id="changePasswordForm">
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-lock mr-2"></i> Change
                        Password</button>
                </form>
            </div>
        </div>
    </div>

</div>
