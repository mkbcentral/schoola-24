 <div class="card">
     <div class="card-header bg-app">
         <h5 class="text-uppercase">
             <i class="{{ $sectionSelected == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"
                 aria-hidden="true"></i>
             {{ $sectionSelected == null ? 'Création nouvelle section' : 'Edition section' }}
         </h5>
     </div>
     <div class="card-body">
         <div class="d-flex justify-content-lg-center">
             <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
         </div>
         <form wire:submit='handlerSubmit'>
             <div>
                 <x-form.label value="{{ __('Nom section') }}" />
                 <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                 <x-errors.validation-error value='form.name' />
             </div>
             <div class="d-flex justify-content-between mt-4">
                 @if ($sectionSelected != null)
                     <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                         class="btn-danger" />
                 @endif
                 <x-form.app-button type='submit'
                     textButton="{{ $sectionSelected == null ? 'Sauvegarder' : 'Modifier' }}"
                     icon="{{ $sectionSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                     class="btn-primary" />
             </div>
         </form>
     </div>
 </div>
