<div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <div>
        <div> <x-form.search-input wire:model.live='q' /></div>
    </div>
    <table class="table table-bordered table-sm table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th>DESCRIPTION</th>
                <th>ICONE</th>
                <th>ROUTE</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($singleAppLinks->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($singleAppLinks as $index => $singleAppLink)
                    <tr wire:key='{{ $singleAppLink->id }}' class=" ">
                        <td class="text-center ">
                            <i class="bi bi-link"></i>
                        </td>
                        <td>{{ $singleAppLink->name }}</td>
                        <td>{{ $singleAppLink->icon }}</td>
                        <td>{{ $singleAppLink->route }}</td>
                        <td class="text-center">
                            @can('manage-payment')
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-secondary btn-sm">
                                    <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                        wire:click='edit({{ $singleAppLink }})' />
                                    <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                        wire:confirm="Voulez-vous vraiment supprimer ?"
                                        wire:click='delete({{ $singleAppLink }})' />
                                </x-others.dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    <span>{{ $singleAppLinks->links('livewire::bootstrap') }}</span>
</div>
