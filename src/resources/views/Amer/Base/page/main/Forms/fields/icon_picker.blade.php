<!-- icon_picker input -->
@php
    // if no iconset was provided, set the default iconset to Font-Awesome
    $field['iconset'] = $field['iconset'] ?? 'fontawesome';
    switch ($field['iconset']) {
        case 'ionicon':
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/ionicons-1.5.2/css/ionicons.min.css');
            break;
        case 'weathericon':
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/weather-icons-1.2.0/css/weather-icons.min.css');
            break;
        case 'mapicon':
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/map-icons-2.1.0/css/map-icons.min.css');
            break;
        case 'octicon':
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/octicons-2.1.2/css/octicons.min.css');
            break;
        case 'typicon':
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/typicons-2.0.6/css/typicons.min.css');
            break;
        case 'elusiveicon':
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/elusive-icons-2.0.0/css/elusive-icons.min.css');
            break;
        case 'meterialdesign':
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/material-design-1.1.1/css)/material-design-iconic-font.min.css');
            break;
        default:
            $fontIconFilePath = asset('js/packages/bootstrap-iconpicker/icon-fonts/font-awesome-5.12.0-1/css/all.min.css');
            break;
    }
    $field['font_icon_file_path'] = $field['font_icon_file_path'] ?? $fontIconFilePath;
@endphp
    <div>
        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="popover" role="icon-selector"></button>
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
    @loadStyleOnce('js/packages/bootstrap-iconpicker/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css')
@endpush
@push('after_scripts')
@loadScriptOnce('js/packages/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.js')
@loadScriptOnce('js/packages/bootstrap-iconpicker/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')
@loadOnce('bpFieldInitIconPickerElement')
        <script>
            function bpFieldInitIconPickerElement(element) {
                var $iconset = element.attr('data-iconset');
                var $iconButton = element.siblings('button[role=icon-selector]');
                var $icon = element.attr('value');
                    $($iconButton).iconpicker({
    align: 'center', // Only in div tag
    arrowClass: 'btn-danger',
    arrowPrevIconClass: 'fas fa-angle-left',
    arrowNextIconClass: 'fas fa-angle-right',
    cols: 10,
    footer: true,
    header: true,
    icon: 'fas fa-bomb',
    iconset: 'fontawesome5',
    labelHeader: '{0} of {1} pages',
    labelFooter: '{0} - {1} of {2} icons',
    placement: 'bottom', // Only in button tag
    rows: 5,
    search: true,
    searchText: 'Search',
    selectedClass: 'btn-success',
    unselectedClass: ''
});
                    element.siblings('button[role=icon-selector]').on('change', function(e) {
                        $(this).siblings('input[type=hidden]').val(e.icon);
                    });
            }
        </script>
@endLoadOnce
    @endpush