<!-- icon_picker input -->
@php
    // if no iconset was provided, set the default iconset to Font-Awesome
    $field['iconset'] = $field['iconset'] ?? 'fontawesome';

    switch ($field['iconset']) {
        case 'ionicon':
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/ionicons-1.5.2/css/ionicons.min.css');
            break;
        case 'weathericon':
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/weather-icons-1.2.0/css/weather-icons.min.css');
            break;
        case 'mapicon':
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/map-icons-2.1.0/css/map-icons.min.css');
            break;
        case 'octicon':
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/octicons-2.1.2/css/octicons.min.css');
            break;
        case 'typicon':
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/typicons-2.0.6/css/typicons.min.css');
            break;
        case 'elusiveicon':
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/elusive-icons-2.0.0/css/elusive-icons.min.css');
            break;
        case 'meterialdesign':
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/material-design-1.1.1/css)/material-design-iconic-font.min.css');
            break;
        default:
            $fontIconFilePath = asset('asd/bootstrap-iconpicker/icon-fonts/font-awesome-5.12.0-1/css/all.min.css');
            break;
    }
    $field['font_icon_file_path'] = $field['font_icon_file_path'] ?? $fontIconFilePath;
@endphp

    <div>
        <button type="button" class="btn btn-light iconpicker btn-sm" role="icon-selector"></button>
        <input
            type="hidden"
            name="{{ $field['name'] }}"
            data-iconset="{{ $field['iconset'] }}"
            data-init-function="bpFieldInitIconPickerElement"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
        >
    </div>
@push('after_styles')
    @loadStyleOnce($field['font_icon_file_path'])
    @loadStyleOnce($field['font_icon_file_path'])
    @loadStyleOnce('asd/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')
@endpush
@push('after_scripts')
@loadScriptOnce('asd/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')
@loadOnce('bpFieldInitIconPickerElement')
        <script>
            function bpFieldInitIconPickerElement(element) {
                var $iconset = element.attr('data-iconset');
                var $iconButton = element.siblings('button[role=icon-selector]');
                var $icon = element.attr('value');
                    $($iconButton).iconpicker({
                        iconset: $iconset,
                        icon: $icon
                    });

                    element.siblings('button[role=icon-selector]').on('change', function(e) {
                        $(this).siblings('input[type=hidden]').val(e.icon);
                    });
            }
        </script>
@endLoadOnce
    @endpush