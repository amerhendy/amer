<?php
if(isset($field['autocomplete'])){$field['attributes']['autocomplete']='on';}
if(isset($field['autofocus'])){$field['attributes']['autofocus']=(boolean) $field['autofocus'];}
if(isset($field['cols'])){$field['attributes']['cols']=(int) $field['cols'];}
if(isset($field['rows'])){$field['attributes']['rows']=(int) $field['rows'];}
if(isset($field['maxlength'])){$field['attributes']['maxlength']=(int) $field['maxlength'];}
if(isset($field['minlength'])){$field['attributes']['minlength']=(int) $field['minlength'];}
if(isset($field['spellcheck'])){$field['attributes']['spellcheck']=(boolean) $field['spellcheck'];}else{$field['attributes']['spellcheck']=true;}
if(isset($field['wrap'])){$field['attributes']['wrap']=$field['wrap'];}
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<textarea
    	name="{{ $field['name'] }}"
        id="{{ $field['name'] }}"
        col="200"
        @include(fieldview('inc.attributes'))
    	>{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}</textarea>
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))