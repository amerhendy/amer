{{-- html5 url input --}}
<?php
if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
?>
@include(fieldview('inc.wrapper_start'))
<div><label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>@include(fieldview('inc.translatable_icon'))</div>
<!-- url.blade -->
        <input
            type="url"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            placeholder="{{ $field['placeholder'] }}"
            @include(fieldview('inc.attributes'))
    	>

            @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
