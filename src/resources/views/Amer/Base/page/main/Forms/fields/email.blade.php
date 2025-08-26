{{-- text input --}}
@php
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    $field['minimum_input_length']=$field['minimum_input_length'] ?? 0 ;
$multiple=false;
if(isset($field['multiple']) && ($field['multiple'] == true || $field['multiple'] == "true" || $field['multiple'] == 1 || $field['multiple'] == '1')){
        $field['attributes']['multiple']='multiple';
        $multiple='multiple';
}
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<input
            type="email"
            placeholder="name@host.xyz"
            name="{{ $field['name'] }}"
            minlength="{{$field['minimum_input_length']}}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
    	>
@if (isset($field['hint']))
    <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
@endif
