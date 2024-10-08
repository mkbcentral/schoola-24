 <tbody>
     @if ($registrations->isEmpty())
         <tr>
             <td colspan="7"><x-errors.data-empty /></td>
         </tr>
     @else
         @foreach ($registrations as $index => $registration)
             <tr wire:key='{{ $registration->student->id }}' class=" ">
                 <td class="text-center {{ $registration->class_changed == true ? 'bg-danger' : '' }}">
                     <x-form.input-check-box idAndFor="{{ $registration->id }}" color="primary"
                         wire:model.live='selectedRegistrations' value='{{ $registration->id }}' />
                 </td>
                 <td class="text-center {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                     {{ $index + 1 }}
                 </td>
                 <td class="text-uppercase {{ $registration->abandoned == true ? 'bg-warning' : '' }}">
                     {{ $registration->code }}
                 </td>
                 <td>{{ $registration->student->name }}</td>
                 <td class="text-center">{{ $registration->student->getFormattedAg() }}</td>
                 <td class="text-center">{{ $registration->student->gender }}</td>
                 <td>{{ $registration?->classRoom?->getOriginalClassRoomName() }}
                 </td>
                 <td class="text-center">
                     <span class="badge text-bg-{{ $registration->is_old ? 'warning' : 'info' }}">
                         {{ $registration->is_old ? 'Ancien' : 'Nouveau' }}
                     </span>
                 </td>
                 <td class="text-center">
                     <x-others.dropdown wire:ignore.self icon="bi bi-three-dots-vertical" class="btn-secondary btn-sm">
                         @can('manage-student')
                             <x-others.dropdown-link iconLink='bi bi-pencil-fill' data-bs-toggle="modal"
                                 data-bs-target="#form-edit-student" labelText='Editer' href="#"
                                 wire:click='edit({{ $registration->student }})' />
                             <x-others.dropdown-link iconLink='bi bi-arrow-left-right' labelText='Basculuer la classe'
                                 data-bs-toggle="modal" data-bs-target="#form-change-class-student"
                                 wire:click='changeClassStudent({{ $registration }})' href="#" />
                             <x-others.dropdown-link iconLink='bi bi-journal-x' labelText='Marquer abandon'
                                 data-bs-toggle="modal" data-bs-target="#form-give-up-student"
                                 wire:click='openMakeGiveUpStudentFom({{ $registration }})' href="#" />
                             <x-others.dropdown-link iconLink='bi bi-trash-fill' labelText='Supprimer' href="#"
                                 wire:click='showDeleteDialog({{ $registration->student }})' />
                         @endcan
                         <x-others.dropdown-link iconLink='bi bi-info-circle-fill' labelText='Voir détails'
                             href="{{ route('student.detail', $registration) }}" />
                         <x-others.dropdown-link wire:click='generateQRCode({{ $registration }})'
                             wire:confirm="Etês-vous sûre de réaliser l'opération" iconLink='bi bi-qr-code-scan'
                             labelText='Générer Qrcode' href="#" />
                     </x-others.dropdown>
                 </td>
             </tr>
         @endforeach
     @endif
 </tbody>
