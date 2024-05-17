<!-- tel-->
<?php
if(isset($field['maxlength'])){$field['attributes']['maxlength']=(int) $field['maxlength'];}
if(isset($field['minlength'])){$field['attributes']['minlength']=(int) $field['minlength'];}
if(isset($field['pattern'])){$field['attributes']['pattern']=$field['pattern'];}
if(isset($field['placeholder'])){$field['attributes']['placeholder']=$field['placeholder'];}
if(isset($field['size'])){$field['attributes']['size']=$field['size'];}
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<input 
name="{{ $field['name'] }}" id="{{ $field['name'] }}" 
value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}" 
type="tel" 
@include(fieldview('inc.attributes'))
>
@if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))