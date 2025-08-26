{{-- html5 range input --}}
<!-- range.blade -->
@php
if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
if(isset($field['min'])){$field['attributes']['min']=(int) $field['min'];}
if(isset($field['max'])){$field['attributes']['max']=(int) $field['max'];}
if(isset($field['step'])){$field['attributes']['step']=$field['step'];}
$field['attributes']['class']='form-range w-100';
@endphp
    <input
        type="range"
        name="{{ $field['name'] }}"
        value="{{ old_empty_or_null($field['name'], '') ??  $field['value'] ?? $field['default'] ?? '' }}"
        @include(fieldview('inc.attributes'))
        id="{{ $field['name'] }}"
        >
        <output class="{{ $field['name'] }}-output" for="{{ $field['name'] }}"></output>
        <script>
            const {{ $field['name'] }} = document.querySelector("#{{ $field['name'] }}");
            const {{ $field['name'] }}output = document.querySelector(".{{ $field['name'] }}-output");
            {{ $field['name'] }}output.textContent = {{ $field['name'] }}.value;
            {{ $field['name'] }}.addEventListener("input", () => {
                {{ $field['name'] }}output.textContent = {{ $field['name'] }}.value;
            });
        </script>
