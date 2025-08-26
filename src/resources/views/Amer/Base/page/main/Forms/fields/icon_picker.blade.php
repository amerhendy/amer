<!-- icon_picker input -->
@php
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    $fontIconFilePath=[
        'ionicon'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/ionicons-1.5.2/css/ionicons.min.css'),
        'weathericon'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/weather-icons-1.2.0/css/weather-icons.min.css'),
        'mapicon'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/map-icons-2.1.0/css/map-icons.min.css'),
        'octicon'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/octicons-2.1.2/css/octicons.min.css'),
        'typicon'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/typicons-2.0.6/css/typicons.min.css'),
        'elusiveicon'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/elusive-icons-2.0.0/css/elusive-icons.min.css'),
        'meterialdesign'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/material-design-1.1.1/css/material-design-iconic-font.min.css'),
        'fontawesome5'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/font-awesome-5.12.0-1/css/all.min.css'),
        'fontawesome4'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/font-awesome-4.7.0/css/font-awesome.min.css'),
        'fontawesome'=>asset('js/packages/bootstrap-iconpicker/icon-fonts/font-awesome-5.12.0-1/css/all.min.css'),
    ];
    $field['iconset'] = $field['iconset'] ?? implode('|',array_keys($fontIconFilePath));
    $field['font_icon_file_path'] = $field['font_icon_file_path'] ?? $fontIconFilePath;
@endphp
@include(fieldview('inc.wrapper_start'))
<div><label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>@include(fieldview('inc.translatable_icon'))</div>
    <div>
        <button type="button" class="btn btn-light btn-sm" data-bs-toggle="popover" role="icon-selector"
        data-iconset="{{$field['iconset']}}"
        data-icon="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? 'fas fa-angle-right' ?? '' }}"
        ></button>
        <input
            type="hidden"
            name="{{ $field['name'] }}"
            data-iconset="{{ $field['iconset'] }}"
            data-init-function="bpFieldInitIconPickerElement"
            placeholder="{{ $field['placeholder'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            @include(fieldview('inc.attributes'))
        >
    </div>
    @include(fieldview('inc.wrapper_end'))
@push('after_styles')
@if(is_array($field['font_icon_file_path']))
@forEach($field['font_icon_file_path'] as $vop)
@loadStyleOnce($vop)
@endforeach
@else
@loadStyleOnce($field['font_icon_file_path'])
@endif

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
                        labelHeader: '{0} of {1} pages',
                        labelFooter: '{0} - {1} of {2} icons',
                        placement: 'bottom', // Only in button tag
                        rows: 5,
                        search: true,
                        searchText: 'Search',
                        selectedClass: 'btn btn-success',
                        unselectedClass: 'btn'
                    });
                    defaultV=$('button[role=icon-selector]').data('icon');
                    $(element).val(defaultV)
                    element.siblings('button[role=icon-selector]').on('change', function(e) {
                        $(this).siblings('input[type=hidden]').val(e.icon);
                    });
            }
        </script>
@endLoadOnce
    @endpush
