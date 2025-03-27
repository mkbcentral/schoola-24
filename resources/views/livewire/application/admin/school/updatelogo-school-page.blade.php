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
        <img x-on:click="$refs.image.click()"
            style="cursor: pointer; border-radius: 50%; border: 5px solid rgb(210, 210, 210); padding: 5px;
            object-fit: cover; transition: transform 0.3s, filter 0.3s;"
            x-bind:src="imagePreview ? imagePreview :
                '{{ asset(Auth::user()->school->logo != null ? 'storage/' . Auth::user()->school->logo : 'images/defautl-user.jpg') }}'"
            alt="User avatar" class="img-fluid mb-4" width="100" height="100"
            x-on:mouseover="this.style.transform='scale(1.1)'; this.style.filter='blur(2px)';"
            x-on:mouseout="this.style.transform='scale(1)'; this.style.filter='blur(0)';">
    </div>
</div>
