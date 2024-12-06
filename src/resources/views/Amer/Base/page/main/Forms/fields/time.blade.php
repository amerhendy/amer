<?php
if(isset($field['maxlength'])){$field['attributes']['maxlength']=(int) $field['maxlength'];}
if(isset($field['minlength'])){$field['attributes']['minlength']=(int) $field['minlength'];}
if(isset($field['placeholder'])){$field['attributes']['placeholder']=$field['placeholder'];}
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<input
            type="time"
            name="{{ $field['name'] }}" id="{{ $field['name'] }}" 
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
    	>        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))