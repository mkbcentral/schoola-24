<div>
    <div class="d-flex flex-wrap align-items-center shadow-sm rounded-3 p-2 gap-2
        bg-white text-dark "
        style="max-width: 380px;">

        <div class="flex-grow-1" style="min-width: 170px;">
            <select id="schoolYearSelect"
                class="form-select w-100 border-primary bg-white text-dark dark:bg-secondary dark:text-light">
                @foreach ($schoolYears as $year)
                    <option value="{{ $year->id }}"> {{ $year->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
