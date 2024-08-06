@props(['selectedCategoryId' => 0, 'classRoomId' => 0])
@php
    $scolarFees = App\Models\ScolarFee::query()
        ->join('category_fees', 'category_fees.id', 'scolar_fees.category_fee_id')
        ->where('category_fees.school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->where('category_fees.school_year_id', App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
        ->where('scolar_fees.category_fee_id', $selectedCategoryId)
        ->where('scolar_fees.class_room_id', $classRoomId)
        ->select('scolar_fees.*')
        ->get();
@endphp
<select id="my-select" class="form-control" {{ $attributes }}>
    <option value="">Choisir...</option>
    @foreach ($scolarFees as $scolarFee)
        <option class="text-uppercase" value="{{ $scolarFee->id }}">{{ $scolarFee->name }}</option>
    @endforeach
</select>
