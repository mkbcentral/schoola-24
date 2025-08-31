<div>
    <div class="d-flex justify-content-center pb-2">
        <x-widget.loading-circular-md wire:loading />
    </div>
    <table class="table table-bordered table-sm  table-hover mt-2">
        <thead class="table-primary">
            <tr class="">
                <th class="text-center">N°</th>
                <th>DESCRIPTION</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        @if ($otherSourceExpenses->isEmpty())
            <tr>
                <td colspan="3"><x-errors.data-empty /></td>
            </tr>
        @else
            <tbody>
                @foreach ($otherSourceExpenses as $index => $otherSourceExpense)
                    <tr wire:key='{{ $otherSourceExpense->id }}' class=" ">
                        <td class="text-center ">
                            {{ $index + 1 }}
                        </td>
                        <td>{{ $otherSourceExpense->name }}</td>
                        <td class="text-center">
                            @can('manage-payment')
                                <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical"
                                    class="btn-outline-secondary btn-sm">
                                    <x-others.dropdown-link iconLink='bi bi-pencil-fill' labelText='Editer' href="#"
                                        wire:click='edit({{ $otherSourceExpense }})' />
                                    <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                        wire:confirm="Voulez-vous vraiment supprimer ?"
                                        wire:click='delete({{ $otherSourceExpense }})' />
                                </x-others.dropdown>
                            @endcan
                        </td>
                    </tr>
                @endforeach
            </tbody>
        @endif
    </table>
</div>
