<div>
    <x-navigation.bread-crumb icon='bi bi-folder-symlink' label="Autres dépenses">
        <x-navigation.bread-crumb-item label='Autres dépenses' />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            @if(Auth::user()->role->name== \App\Enums\RoleType::SCHOOL_FINANCE)
                <div class="col-md-8 mt-2">
                    <livewire:application.finance.expense.list.list-other-expense-page />
                </div>
                <div class="col-md-4 mt-2">
                    <livewire:application.finance.expense.form.form-other-expense-page />
                </div>
            @else
                <div class="col-md-12 mt-2">
                    <livewire:application.finance.expense.list.list-other-expense-page />
                </div>
            @endif

        </div>
    </x-content.main-content-page>
</div>
