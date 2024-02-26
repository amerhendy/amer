{{-- html5 url input --}}
@include(fieldview('inc.wrapper_start'))
<div><label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>@include(fieldview('inc.translatable_icon'))</div>
<!-- url.blade -->
        <input
            type="url"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
    	>

            @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))