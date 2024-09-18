<div>
    <x-navigation.bread-crumb icon='bi bi-card-heading' label="Categorie salaire">
        <x-navigation.bread-crumb-item label='CategorySalaire' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-8 mt-2">
                <div class="card">
                    <div class="row card-body">
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <x-others.list-title title='Liste des CATEGORIE' icon='bi bi-card-checklist' />
                            </div>
                            <div class="mt-2">
                                <div class="d-flex justify-content-center pb-2">
                                    <x-widget.loading-circular-md wire:loading />
                                </div>
                                <table class="table table-bordered table-hover table-sm">
                                    <thead class="bg-app">
                                        <tr class="cursor-hand">
                                            <th class="text-center">#</th>
                                            <th>
                                                CATEGORIE
                                            </th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($categorySalaries->isEmpty())
                                            <tr>
                                                <td colspan="5"><x-errors.data-empty /></td>
                                            </tr>
                                        @else
                                            @foreach ($categorySalaries as $index => $categorySalary)
                                                <tr wire:key='{{ $categorySalary->id }}'>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>{{ $categorySalary->name }}</td>
                                                    <td class="text-center">
                                                        <x-form.app-button type='button' icon="bi bi-pencil-fill"
                                                            class="btn-sm app-btn"
                                                            wire:click='edit({{ $categorySalary }})' />
                                                        <x-form.app-button
                                                            wire:confirm="Est-vous sur de réaliser l'opération"
                                                            type='button' icon="bi bi-trash-fill"
                                                            class="btn-danger btn-sm"
                                                            wire:click='delete({{ $categorySalary }})' />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif

                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>{{ $categorySalaries->links('livewire::bootstrap') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-2">
                <livewire:application.finance.salary.form.form-category-salary-page />
            </div>
        </div>
    </x-content.main-content-page>
</div>
