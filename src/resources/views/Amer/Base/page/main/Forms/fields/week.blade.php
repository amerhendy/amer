<!--week-->
<?php
if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
}
$field['minimum_input_length']=$field['minimum_input_length'] ?? 0 ;
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<input
            type="week"
            name="{{ $field['name'] }}"
            id="{{ $field['name'] }}"
            placeholder="{{ $field['placeholder'] }}"
            minlength="{{$field['minimum_input_length']}}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
        >
@if (isset($field['hint']))
    <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
@endif
@include(fieldview('inc.wrapper_end'))
