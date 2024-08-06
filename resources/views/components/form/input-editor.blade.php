<div>
    @props(['id','value'])
    <textarea {{$attributes}} id="note" data-{{$id}}="@this"
              placeholder="Saisir les commentaire ici..." class="form-control" >
        {!! $value !!}
    </textarea>

    @push('js')
        <script type="module">
            ClassicEditor
                .create( document.querySelector( '#{{$id}}' ) )
                .then( editor => {
                   editor.model.document.on('change:data',()=>{
                       @this.set('{{$id}}', editor.getData());
                   })
                } )
                .catch( error => {
                    console.error( error );
                } );
        </script>
    @endpush
</div>
