@php
$current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<input class="form-control" list="{{ $field['name'] }}_list" id="{{ $field['name'] }}" name="{{ $field['name'] }}" placeholder="Type to search..." style="width: 100%" @include(fieldview('inc.attributes')) value="{{$current_value ?? ''}}">
<datalist id="{{ $field['name'] }}_list">
@isset($field['options'])
@foreach($field['options'] as $name)
    @if(!is_array($name))
        <option value="{{$name}}">
    @endif
@endforeach
@endisset
</datalist>
