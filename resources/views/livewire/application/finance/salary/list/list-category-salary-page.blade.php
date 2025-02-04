<div>
    <x-navigation.bread-crumb icon='bi bi-card-heading' label="Categorie salaire">
        <x-navigation.bread-crumb-item label='CategorySalaire' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div>
            <div class="d-flex justify-content-end">
                <div class="d-flex justify-content-center pb-2">
                    <x-widget.loading-circular-md wire:loading />
                </div>
                <x-form.app-button wire:click='newCategorySalary' data-bs-toggle="modal"
                    data-bs-target="#form-category-salary" textButton='Nouveau' icon="bi bi-plus-circle"
                    class="btn-primary" />
            </div>
            <table class="table table-bordered table-hover table-sm mt-2">
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
                                    @can('manage-payment')
                                        <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                            class="btn-secondary btn-sm">
                                            <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer'
                                                href="#" wire:click='edit({{ $categorySalary }})'
                                                data-bs-toggle="modal" data-bs-target="#form-category-salary" />
                                            <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer'
                                                href="#" wire:confirm="Voulez-vous vraiment supprimer ?"
                                                wire:click='delete({{ $categorySalary }})' />
                                        </x-others.dropdown>
                                    @endcan
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
        <livewire:application.finance.salary.form.form-category-salary-page />
    </x-content.main-content-page>
</div>
