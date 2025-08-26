<!--show_fields.blade-->{{-- Show the inputs --}}
@foreach ($fields as $field)
    <!-- load the view from type and view_namespace attribute if set -->
    @php
        $fieldsViewNamespace = $field['view_namespace'] ?? 'Amer::fields';
    @endphp
    @include(fieldview($field['type']), ['field' => $field, 'inlineCreate' => true])
@endforeach
@stack('fields_scripts')

@stack('fields_styles')

<!--show_fields.blade-->
