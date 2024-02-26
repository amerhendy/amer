<!--color.blade-->
{{-- html5 color input --}}
        <input
            type="color"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
            id="{{ $field['name'] }}"
    	>