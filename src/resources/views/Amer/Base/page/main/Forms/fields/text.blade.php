<!-- text.blade.php -->
<?php
if(isset($field['maxlength'])){$field['attributes']['maxlength']=(int) $field['maxlength'];}
if(isset($field['minlength'])){$field['attributes']['minlength']=(int) $field['minlength'];}
if(isset($field['pattern'])){$field['attributes']['pattern']=$field['pattern'];}
if(isset($field['placeholder'])){$field['attributes']['placeholder']=$field['placeholder'];}
if(isset($field['size'])){$field['attributes']['size']=(int) $field['size'];}
if(isset($field['spellcheck'])){$field['attributes']['spellcheck']=true;}
if(isset($field['autocorrect'])){$field['attributes']['autocorrect']='on';}
?>
@include(fieldview('inc.wrapper_start'))
<div><label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>@include(fieldview('inc.translatable_icon'))</div>
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            id="{{ $field['name'] }}"
            @include(fieldview('inc.attributes'))
        >
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))