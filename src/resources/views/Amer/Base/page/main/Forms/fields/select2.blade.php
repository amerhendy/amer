@php
$current_value = old($field['name']) ?? $field['value'] ?? $field['default'] ?? '';
if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {
        $current_value = $current_value->getKey();
    }
    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
    $field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']);
    $language=Str::replace('_', '-', app()->getLocale());
@endphp
<select 
name="{{ $field['name'] }}" 
style="width: 100%" 
data-field-is-inline="{{var_export($inlineCreate ?? false)}}" 
data-init-function="bpFieldInitSelect2Element" 
data-language="{{ str_replace('_', '-', app()->getLocale()) }}" 
@include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_field'])>
@if(isset($field['allows_null']) && ($field['allows_null'] == true || $field['allows_null'] == "true" || $field['allows_null'] == 1))
    <option value="">-</option>
@endif
    @if (count($options))
        @foreach ($options as $option)
        @if($current_value == $option->getKey())
                    <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
                @endif
        @endforeach
    @endif
</select>
@loadOnce('bpFieldInitSelect2Element')
@push('after_styles')
@loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
@loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
@endpush
@push('after_scripts')
@loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
@loadScriptOnce('js/packages/select2/dist/js/i18n/'.$language.'.js')
<script>
    function bpFieldInitSelect2Element(element) {
        if (!element.hasClass("select2-hidden-accessible")) 
                {
                    let $isFieldInline = element.data('field-is-inline');
                    element.select2({
                        language:"{{$language}}",
                        fir:"rtl",
                        theme: "bootstrap-5",
                        //dropdownParent: $("#form-select-sm").parent(),
                        dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : document.body
                    });
                }
        }
</script>
@endpush
@endLoadOnce