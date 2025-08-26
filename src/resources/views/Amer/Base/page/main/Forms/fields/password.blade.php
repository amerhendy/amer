{{-- password --}}

@php
    // autocomplete off, if not otherwise specified
    if (!isset($field['attributes']['autocomplete'])) {
        $field['attributes']['autocomplete'] = "off";
    }
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
        <span class='input-group-text'>
<i class='fa fa-key'></i>
<input
            type="password"
            placeholder="{{ $field['placeholder'] }}"
            name="{{ $field['name'] }}"
            @include(fieldview('inc.attributes'))
            id="{{ $field['name'] }}"
    	>
</span>
@if (isset($field['hint']))
    <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
@endif
@include(fieldview('inc.wrapper_end'))
