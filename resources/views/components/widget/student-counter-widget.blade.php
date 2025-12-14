 @props(['label' => '', 'value' => 0, 'bg' => ''])
 <div class="card h-100 border-0 shadow-sm {{ $bg }}">
     <div class="card-body">
         <div class="d-flex align-items-center">
             <div class="shrink-0">
                 <div class="avatar-circle bg-primary bg-opacity-10 text-primary">
                     <i class="bi bi-mortarboard"></i>
                 </div>
             </div>
             <div class="grow ms-3">
                 <div class="small ">{{ $label }}</div>
                 <div class="fs-5 fw-semibold">{{ $value }}</div>
             </div>
             <div class="dropdown">
                 <button class="btn btn-link  p-0" data-bs-toggle="dropdown">
                     <i class="bi bi-three-dots-vertical"></i>
                 </button>
                 <ul class="dropdown-menu">
                     {{ $slot }}
                 </ul>
             </div>
         </div>
     </div>
 </div>
