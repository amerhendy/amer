{{-- html5 datetime input --}}
<!-- datetime -->
@php
// if the column has been cast to Carbon or Date (using attribute casting)
// get the value as a date string
if (isset($field['value']) && ($field['value'] instanceof \Carbon\CarbonInterface)) {
    $field['value'] = $field['value']->toDateTimeString();
}

$timestamp = strtotime(old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '');

$value = $timestamp ? date('Y-m-d\TH:i:s', $timestamp) : '';
@endphp

@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
        <input
            type="datetime-local"
            name="{{ $field['name'] }}"
            value="{{ $value }}"
            @include(fieldview('inc.attributes'))
        >
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))