 @props(['title' => '', 'icon' => ''])
 <div class="dropdown">
     <button {{ $attributes->merge([
         'class' => 'btn btn-secondary dropdown-toggle',
     ]) }} type="button"
         data-bs-toggle="dropdown" aria-expanded="false">
         <i class="fw-bold {{ $icon }}" aria-hidden="true"></i>
         {{ $title }}
     </button>
     <ul class="dropdown-menu dropdown-menu-end" style="">
         {{ $slot }}
     </ul>

 </div>
