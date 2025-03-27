<x-print-layout>
    <div class="">
        <x-widget.school-header-infos />
        <h3 class="text-center">EFFECTIF PAR OPTION</h3>
        @foreach ($options as $index => $option)
            <h5>{{ $index + 1 }}.{{ $option->name }}</h5>
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>CLASSE</th>
                        <th class="text-center">EFFECTIF</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($option->classRooms as $classRoom)
                        <tr>
                            <td>{{ $classRoom->getOriginalClassRoomName() }}</td>
                            <td style="text-align: center">{{ $classRoom->getRegistrationCountForCurrentSchoolYear() }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="page-break"></div>
        @endforeach
    </div>
</x-print-layout>
