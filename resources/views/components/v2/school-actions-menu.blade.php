@props(['school'])

<div class="dropdown ms-2">
    <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
        <i class="bi bi-three-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow">
        @can('update', $school)
            <li>
                <button class="dropdown-item" wire:click="editSchool({{ $school->id }})">
                    <i class="bi bi-pencil text-primary me-2"></i>
                    Modifier
                </button>
            </li>
            <li>
                <button class="dropdown-item" wire:click="editSchoolLogo({{ $school->id }})">
                    <i class="bi bi-image text-success me-2"></i>
                    Modifier le logo
                </button>
            </li>
        @endcan

        @can('manageUsers', $school)
            <li>
                <button class="dropdown-item" wire:click="viewSchoolUsers({{ $school->id }})">
                    <i class="bi bi-people text-info me-2"></i>
                    Utilisateurs
                </button>
            </li>
        @endcan

        @can('toggleStatus', $school)
            <li>
                <button class="dropdown-item" 
                    wire:click="toggleSchoolStatus({{ $school->id }})"
                    wire:confirm="Êtes-vous sûr de vouloir changer le statut?">
                    <i class="bi bi-toggle-on text-warning me-2"></i>
                    Changer statut
                </button>
            </li>
        @endcan

        @can('delete', $school)
            <li><hr class="dropdown-divider"></li>
            <li>
                <button class="dropdown-item text-danger" 
                    wire:click="deleteSchool({{ $school->id }})"
                    wire:confirm="Êtes-vous sûr de vouloir supprimer cette école?">
                    <i class="bi bi-trash me-2"></i>
                    Supprimer
                </button>
            </li>
        @endcan
    </ul>
</div>
