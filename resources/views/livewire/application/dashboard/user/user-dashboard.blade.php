<div>
    <div class="row">
        <div class="col-md-3">
            <x-widget.card-info label="Users en ligne" icon="bi-people-fill" value="{{ $onlines }}" link="admin.main"
                linkLabel="Voir détail" bg="bg-success" />
        </div>
        <div class="col-md-3">
            <x-widget.card-info label="Users hors ligne" link="admin.main" icon="bi-people-fill"
                value="{{ $offlines }}" linkLabel="Voir détail" bg="bg-warning" />
        </div>
        <div class="col-md-3">
            <x-widget.card-info label="Users bloqués" link="admin.main" icon="bi-people-fill"
                value="{{ $desactivateds }}" linkLabel="Voir détail" bg="bg-danger" />
        </div>
    </div>
    <h4><i class="bi bi-person-check-fill"></i> Utilisateurs en lignes</h4>
    <table class="table table-bordered table-hover table-sm mt-2">
        <thead class="bg-success text-white">
            <tr class="cursor-hand">
                <th class="text-center">#</th>
                <th class="text-center">Nom</th>
                <th>ROLE</th>
            </tr>
        </thead>
        <tbody>
            @if ($listOnlines->isEmpty())
                <tr>
                    <td colspan="10"><x-errors.data-empty /></td>
                </tr>
            @else
                @foreach ($listOnlines as $index => $user)
                    <tr wire:key='{{ $user->id }}'>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user?->role?->name }}</td>
                    </tr>
                @endforeach
            @endif

        </tbody>
    </table>
</div>
