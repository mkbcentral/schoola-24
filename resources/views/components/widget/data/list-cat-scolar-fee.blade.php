@php
     if (\Illuminate\Support\Facades\Auth::user()->role->name == \App\Enums\RoleType::SCHOOL_FINANCE){
         $categoryFees = App\Models\CategoryFee::query()
        ->where('school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->where('school_year_id', App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
        ->get();
    }else{
         $categoryFees = App\Models\CategoryFee::query()
        ->where('school_id', App\Models\School::DEFAULT_SCHOOL_ID())
        ->where('school_year_id', App\Models\SchoolYear::DEFAULT_SCHOOL_YEAR_ID())
        ->where('is_accessory',true)
        ->get();
    }
@endphp

<select id="my-select" class="form-select form-control" {{ $attributes }}>
    <option disabled>Choisir...</option>
    <option value="0">Tout</option>
    @foreach ($categoryFees as $cat)
        <option class="text-uppercase" value="{{ $cat->id }}">{{ $cat->name }}</option>
    @endforeach
</select>
