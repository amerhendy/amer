<!-- address.blade -->
@php
    $language=str_replace('_', '-', app()->getLocale());
    $field['store_as_json'] = $field['store_as_json'] ?? false;
    $field['value'] = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';

    // the field should work whether or not Laravel attribute casting is used
    if (isset($field['value']) && (is_array($field['value']) || is_object($field['value']))) {
        $field['value'] = json_encode($field['value']);
    }
    $field['delay'] = $field['delay'] ?? 500;
@endphp
        <select
        name="{{ $field['name'] }}"
        style="width:100%"
        data-init-function="bpFieldInitSelect2FromAjaxPingMaps"
        data-placeholder="{{ $field['placeholder'] ?? ''}}"
        data-minimum-input-length="{{ $field['minimum_input_length'] ?? 2}}"
        data-data-source="https://dev.virtualearth.net/REST/v1/Locations/"
        data-ajax-delay="{{ $field['delay'] }}"
        data-language="{{ $language }}"
        data-ajax-more="{{ json_encode(['async'=>true,'crossDomain'=>true]) }}";
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control'])
        ></select>
    @push('after_styles')
    @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
    @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
    @endpush
    @push('after_scripts')
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/'.$language.'.js')
    @loadScriptOnce('js/Amer/forms/select2.js');
    @loadScriptOnce('js/Amer/forms/address.js');
    @loadOnce('bpFieldInitSelect2FromAjaxPingMaps')
    <script>
        var pingMapsKey="{{config('amer.ping.Maps.Key') ?? null}}";
    </script>
    @endLoadOnce
    @endpush
