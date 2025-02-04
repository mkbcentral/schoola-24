<div>
    <div class="row mb-4">
        @if (Auth::user()->role->name == 'SCHOOL_FINANCE' || Auth::user()->role->name == 'SCHOOL_BOSS')
            <div class="col-md-3">
                <x-widget.card-info data-bs-toggle="modal" data-bs-target="#form-cost-recipe" label="RECETTES MINERVAL"
                    icon="bi-cash-coin" value="{{ app_format_number($revenue, 1) }}$" linkLabel="Voir détail"
                    bg="bg-info" />
            </div>
            <div class="col-md-3">
                <x-widget.card-info data-bs-toggle="modal" data-bs-target="#dialog-cost-expense"
                    label="Depense sur le frais" icon="bi-wallet2" :value="app_format_number($expense->total_usd, 1)" link=""
                    linkLabel="Voir détail" bg="bg-danger" />
            </div>
            <div class="col-md-3" data-bs-toggle="modal" data-bs-target="#dialog-other-expense">
                <x-widget.card-info label="Autres dépenses" icon="bi-wallet2" :value="app_format_number($otherExpense->total_usd, 1)" link=""
                    linkLabel="Voir détail" bg="bg-success" />
            </div>
            <div class="col-md-3">
                <x-widget.card-info label="Total élèves" icon="bi-people" :value="$count_all" link="student.list"
                    linkLabel="Voir détail" bg="bg-primary" />
            </div>
        @elseif (Auth::user()->role->name == 'SCHOOL_MANAGER')
            <div class="col-md-3">
                <x-widget.card-info data-bs-toggle="modal" data-bs-target="#form-cost-recipe" label="RECETTES MINERVAL"
                    icon="bi-cash-coin" value="{{ app_format_number($revenue, 1) }}$" linkLabel="Voir détail"
                    bg="bg-info" />
            </div>
            <div class="col-md-3">
                <x-widget.card-info label="Total élèves" icon="bi-people" :value="$count_all" link="student.list"
                    linkLabel="Voir détail" bg="bg-primary" />
            </div>
        @endif

    </div>
</div>
