<!--date-->
{{-- html5 date input --}}
@php
if(isset($field['min'])){$field['attributes']['min']=(int) $field['min'];}
if(isset($field['max'])){$field['attributes']['max']=(int) $field['max'];}
if(isset($field['step'])){$field['attributes']['step']=$field['step'];}
@endphp
<?php
// if the column has been cast to Carbon or Date (using attribute casting)
// get the value as a date string
if (isset($field['value']) && ($field['value'] instanceof \Carbon\CarbonInterface)) {
    $field['value'] = $field['value']->toDateString();
}
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
        <input
            type="date"
            name="{{ $field['name'] }}"
            value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
        >
        
        @push('after_scripts')
            <script>
            $('#Name').on('change',function(){
                alert($('#Name').val());
            });
            </script>
            @endpush

            @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))