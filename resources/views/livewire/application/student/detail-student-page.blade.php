<div>
    <x-navigation.bread-crumb icon='bi bi-person-video2' label="Informations de l'élève" color=''>
        <x-navigation.bread-crumb-item label="Informations de l'élève" />
        <x-navigation.bread-crumb-item label='Liste des élèves' isLinked=true link="student.list" />
        <x-navigation.bread-crumb-item label='Dasnboard' isLinked=true link="dashboard.main" />
    </x-navigation.bread-crumb>
    <x-content.main-content-page>
        <div class="row">
            <div class="col-md-4">
                <livewire:application.student.widget.student-card-info-widget :registration="$registration" />
            </div>
            <div class="col-md-8">
                <livewire:application.widgets.student.student-payments-info-widget :registration="$registration" />
            </div>

    </x-content.main-content-page>

</div>
