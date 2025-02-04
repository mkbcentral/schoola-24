 <div class="card">
     <div class="card-header bg-app">
         <h5 class=" text-uppercase">
             <i class="{{ $optionSelected == null ? 'bi bi-plus-circle-fill' : 'bi bi-pencil-fill' }}"
                 aria-hidden="true"></i>
             {{ $optionSelected == null ? 'Création nouvelle option' : 'Edition option' }}
         </h5>
     </div>
     <div class="card-body">
         <div class="d-flex justify-content-center pb-2">
             <x-widget.loading-circular-md wire:loading wire:target='handlerSubmit' />
         </div>
         <form wire:submit='handlerSubmit'>
             <div>
                 <x-form.label value="{{ __('Nom option') }}" />
                 <x-form.input type='text' wire:model.blur='form.name' :error="'form.name'" />
                 <x-errors.validation-error value='form.name' />
             </div>
             <div>
                 <x-form.label value="{{ __('Type section') }}" />
                 <x-widget.data.list-section wire:model.blur='form.section_id' :error="'form.section_id'" />
                 <x-errors.validation-error value='form.section_id' />
             </div>
             <div class="d-flex justify-content-between mt-4">
                 @if ($optionSelected != null)
                     <x-form.app-button wire:click='cancelUpdate' type='button' textButton="Annuler" icon="bi bi-x"
                         class="btn-danger" />
                 @endif
                 <x-form.app-button type='submit'
                     textButton="{{ $optionSelected == null ? 'Sauvegarder' : 'Modifier' }}"
                     icon="{{ $optionSelected == null ? 'bi bi-floppy-fill' : 'bi bi-pencil-fill' }}"
                     class="btn-primary" />

             </div>
         </form>
     </div>
 </div>
