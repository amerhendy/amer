<!--color.blade-->
{{-- html5 color input --}}
<?php
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
?>
@include(fieldview('inc.wrapper_start'))
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
<div>
        <input
            type="color"
            name="{{ $field['name'] }}"
            placeholder="{{ $field['placeholder'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
            id="{{ $field['name'] }}"
    	>
</div>
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
