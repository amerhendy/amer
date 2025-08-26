<!-- datalist -->
@php
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
$current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
@endphp
@include(fieldview('inc.wrapper_start'))
<div><label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>@include(fieldview('inc.translatable_icon'))</div>
<input class="form-control" list="{{ $field['name'] }}_list" id="{{ $field['name'] }}" name="{{ $field['name'] }}" placeholder="{{ $field['placeholder'] }}" style="width: 100%" @include(fieldview('inc.attributes')) value="{{$current_value ?? ''}}">
<datalist id="{{ $field['name'] }}_list">
@isset($field['options'])
@foreach($field['options'] as $name)
    @if(!is_array($name))
        <option value="{{$name}}">
    @endif
@endforeach
@endisset
</datalist>
@if (isset($field['hint']))
<small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
@endif
@include(fieldview('inc.wrapper_end'))
<!-- datalist -->
