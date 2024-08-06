 <div class="card p-2 mt-2">
     <div class="row row-cols-2 row-cols-lg-3 g-2 g-lg-2">
         <div class="col">
             <form action="" class="card">
                 <div class="d-flex justify-center">
                     <div class="avatar-wrapper mt-2">
                         <img id="avatar-preview" src="{{ asset('images/defautl-user.jpg') }}" alt="logo">
                         <div class="avatar-edit">
                             <input type="file" id="avatar-upload" accept="image/*">
                             <label for="avatar-upload">Changer</label>
                         </div>
                     </div>
                 </div>
                 <div class="card-body">
                     <div class="">
                         <x-form.label value="{{ __('Nom école') }}" class="" />
                         <x-form.input type='text' wire:model.blur='form.name' icon='bi bi-house-gear-fill'
                             :error="'form.name'" style="height: 40px" />
                         <x-errors.validation-error value='form.name' />
                     </div>
                     <div class="mt-2">
                         <x-form.label value="{{ __('Adresse email') }}" class="" />
                         <x-form.input type='text' wire:model.blur='form.email' icon='bi bi-envelope-at-fill'
                             :error="'form.email'" style="height: 40px" />
                         <x-errors.validation-error value='form.email' />
                     </div>
                     <div class="mt-2">
                         <x-form.label value="{{ __('Contact') }}" class="" />
                         <x-form.input type='text' wire:model.blur='form.phone' icon='bi bi-telephone-fill'
                             :error="'form.phone'" style="height: 40px" />
                         <x-errors.validation-error value='form.phone' />
                     </div>
                     <div class="form-group mt-2">
                         <label for="my-select">Mode</label>
                         <select class="form-select" aria-label="Default select example"
                             wire:model.blur='form.app_status'>
                             <option selected>Choisir</option>
                             @foreach (App\Enums\SchoolAppEnum::getValues() as $item)
                                 <option value="{{ $item }}">{{ $item }}</option>
                             @endforeach
                         </select>
                         <x-errors.validation-error value='form.app_status' />
                     </div>
                     <div class="form-group mt-2">
                         <label for="my-select">Status</label>
                         <select class="form-select" aria-label="Default select example"
                             wire:model.blur='form.school_status'>
                             <option selected>Choisir</option>
                             @foreach (App\Enums\SchoolEnum::getValues() as $item)
                                 <option value="{{ $item }}">{{ $item }}</option>
                             @endforeach
                         </select>
                         <x-errors.validation-error value='form.school_status' />
                     </div>
                 </div>
             </form>
         </div>
     </div>
     @push('js')
         <script type="module">
             // Handle avatar image preview
             document.getElementById('avatar-upload').addEventListener('change', function(e) {
                 const file = e.target.files[0];
                 if (file) {
                     const reader = new FileReader();
                     reader.onload = function(e) {
                         document.getElementById('avatar-preview').src = e.target.result;
                     }
                     reader.readAsDataURL(file);
                 }
             });
         </script>
     @endpush
 </div>
