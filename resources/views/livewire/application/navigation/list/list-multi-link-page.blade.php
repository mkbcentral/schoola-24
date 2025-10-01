<div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <div>
        <div> <x-form.search-input wire:model.live='q' /></div>
    </div>
    <table class="table table-bordered table-sm table-hover mt-2">
        <thead class="table-primary">
            <tr>
                <th class="text-center">N°</th>
                <th>DESCRIPTION</th>
                <th>ICONE</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($multiAppLinks->isEmpty())
            <tr>
                <td colspan="7"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($multiAppLinks as $index => $multiAppLink)
                    <tr wire:key='{{ $multiAppLink->id }}' class=" ">
                        <td class="text-center ">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $multiAppLink->name }}</td>
                        <td>{{ $multiAppLink->icon }}</td>
                        <td class="text-center">
                            <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                class="btn-outline-secondary btn-sm">
                                <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                    wire:click='edit({{ $multiAppLink }})' />
                                <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                    wire:confirm="Voulez-vous vraiment supprimer ?"
                                    wire:click='delete({{ $multiAppLink }})' />
                            </x-others.dropdown>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
    <span>{{ $multiAppLinks->links('livewire::bootstrap') }}</span>
</div>
