@props(['active' => '', 'link' => ''])
<div class="card-body">
    <div class="tab-content">
        <div class="{{ $active }} tab-pane" id="{{ $link }}">
            {{ $slot }}
        </div>
    </div>
