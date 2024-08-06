 @props(['title' => '', 'icon' => ''])
 <div class="btn-group">
     <button type="button" {{ $attributes->merge(['class' => 'btn dropdown-icon']) }} class=""
         data-bs-toggle="dropdown" aria-expanded="false">
         <i class="fw-bold {{ $icon }}" aria-hidden="true"></i>
         {{ $title }}
     </button>
     <div class="dropdown-menu" role="menu" style="">
         {{ $slot }}
     </div>
 </div>
