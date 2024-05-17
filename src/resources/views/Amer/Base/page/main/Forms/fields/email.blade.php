{{-- text input --}}
@php
$multiple=false;
if(isset($field['multiple']) && ($field['multiple'] == true || $field['multiple'] == "true" || $field['multiple'] == 1 || $field['multiple'] == '1')){
        $field['attributes']['multiple']='multiple';
        $multiple='multiple';
}
@endphp
<input
            type="email"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
    	>