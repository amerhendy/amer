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
        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            id="{{ $field['name'] }}"
            @include(fieldview('inc.attributes'))
        >