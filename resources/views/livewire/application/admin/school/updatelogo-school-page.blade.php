<div>
    <div x-data={imagePreview:null}>
        <input hidden type="file" wire:model.live='logo' x-ref='image'
            x-on:change="
                const reader= new FileReader();
                reader.onload=(event)=>{
                    imagePreview=event.target.result;
                }
               reader.readAsDataURL($refs.image.files[0]);
            ">
        <img x-on:click="$refs.image.click()" style="cursor: pointer;"
            x-bind:src="imagePreview ? imagePreview :
                '{{ asset(Auth::user()->school->logo != null ? 'storage/' . Auth::user()->school->logo : 'images/defautl-user.jpg') }}'"
            alt="User avatar" class="avatar mb-4">
    </div>
</div>
