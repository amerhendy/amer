{{-- password --}}

@php
    // autocomplete off, if not otherwise specified
    if (!isset($field['attributes']['autocomplete'])) {
        $field['attributes']['autocomplete'] = "off";
    }
@endphp        <span class='input-group-text'>
<i class='fa fa-key'></i>
<input
            type="password"
            name="{{ $field['name'] }}"
            @include(fieldview('inc.attributes'))
            id="{{ $field['name'] }}" 
    	>
</span>