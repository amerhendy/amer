{{-- number input --}}
<!-- number -->
@php
if(isset($field['min'])){$field['attributes']['min']=(int) $field['min'];}
if(isset($field['max'])){$field['attributes']['max']=(int) $field['max'];}
if(isset($field['step'])){$field['attributes']['step']=$field['step'];}
@endphp
        <input
        	type="number"
        	name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
        	>