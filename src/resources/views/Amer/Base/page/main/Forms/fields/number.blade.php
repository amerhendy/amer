{{-- number input --}}
<!-- number -->
@php
if(isset($field['min'])){$field['attributes']['min']=(int) $field['min'];}
if(isset($field['max'])){$field['attributes']['max']=(int) $field['max'];}
if(isset($field['step'])){$field['attributes']['step']=$field['step'];}
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
        <input
        	type="number"
        	name="{{ $field['name'] }}" 
            id="{{ $field['name'] }}" 
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
        	>
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))