<!-- select2 from array -->
<?php
    $field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']);
    $field['wrapper'] = $field['wrapper'] ?? $field['wrapperAttributes'] ?? [];
    $field['wrapper']['class'] = $field['wrapper']['class'] ?? 'form-group col-sm-12';
    foreach($field['wrapper'] as $attributeKey => $value) {
        $field['wrapper'][$attributeKey] = !is_string($value) && is_callable($value) ? $value($Amer, $field, $entry ?? null) : $value ?? '';
    }
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
        name="{{ $field['name'] }}@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)[]@endif"
        id="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2FromArrayElement"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_from_array'])
        @if (isset($field['allows_multiple']) && $field['allows_multiple']==true)multiple @endif
        >

        @if ($field['allows_null'])
            <option value="">-</option>
        @endif

        @if (count($field['options']))
            @foreach ($field['options'] as $key => $value)
                @if((old(square_brackets_to_dots($field['name'])) && (
                        $key == old(square_brackets_to_dots($field['name'])) ||
                        (is_array(old(square_brackets_to_dots($field['name']))) &&
                        in_array($key, old(square_brackets_to_dots($field['name'])))))) ||
                        (null === old(square_brackets_to_dots($field['name'])) &&
                            ((isset($field['value']) && (
                                        $key == $field['value'] || (
                                                is_array($field['value']) &&
                                                in_array($key, $field['value'])
                                                )
                                        )) ||
                                (!isset($field['value']) && isset($field['default']) &&
                                ($key == $field['default'] || (
                                                is_array($field['default']) &&
                                                in_array($key, $field['default'])
                                            )
                                        )
                                ))
                        ))
                    <option value="{{ $key }}" selected>{{ $value }}</option>
                @else
                    <option value="{{ $key }}">{{ $value }}</option>
                @endif
            @endforeach
        @endif
    </select>

    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))
    @push('after_styles')
    @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
    @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
    @endpush
    @push('after_scripts')
    <!-- include select2 js-->
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @if (app()->getLocale() !== 'en')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
    @loadOnce('bpFieldInitSelect2FromArrayElement')
    <script>
        function bpFieldInitSelect2FromArrayElement(element) {
            if (!element.hasClass("select2-hidden-accessible"))
                {
                    let $isFieldInline = element.data('field-is-inline');

                    element.select2({
                        theme: "bootstrap-5",
                        dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : document.body
                    }).on('select2:unselect', function(e) {
                        if ($(this).attr('multiple') && $(this).val().length == 0) {
                            $(this).val(null).trigger('change');
                        }
                    });
                }
        }
    </script>
    @endLoadOnce
    @endpush