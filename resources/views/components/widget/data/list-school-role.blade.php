@props(['optionId' => 0])
@php
    $roles = App\Models\Role::query()->where('is_for_school', true)->get();
@endphp

<select id="my-select" class="form-control" {{ $attributes }}>
    <option>Choisir...</option>
    @foreach ($roles as $role)
        <option class="text-uppercase" value="{{ $role->id }}">{{ $role->name }}</option>
    @endforeach
</select>
