@props(['bg'=>'btn-primary'])
<div class="d-flex align-items-center">
    <div class="card-tools">
        <div class="input-group input-group-sm">
            <input {{$attributes}}  type="text"
                   class="form-control" placeholder="Recheche ici...">
            <div class="input-group-append">
                <div class="btn {{$bg}}">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </div>
</div>
