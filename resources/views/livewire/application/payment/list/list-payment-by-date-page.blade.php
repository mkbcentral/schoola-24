 <x-modal.build-modal-fixed idModal='list-payment-by-date' size='fullscreen' headerLabel="PAIEMENTS JOURNALIERS"
     headerLabelIcon='bi bi-arrow-left-right'>
     <div class="d-flex justify-content-center pb-2">
         <x-widget.loading-circular-md wire:loading wire:target='save' />
         <x-widget.loading-circular-md wire:loading wire:target='getRegistration' />
     </div>

 </x-modal.build-modal-fixed>
