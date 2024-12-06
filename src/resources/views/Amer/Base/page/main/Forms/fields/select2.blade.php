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
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
<select
name="{{ $field['name'] }}[]"
style="width: 100%"
data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
data-init-function="bpFieldInitSelect2Element"
data-read-only="{{$field['readonly'] ?? 'false'}}"
data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
@include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_field'])>
@if(isset($field['allows_null']) && ($field['allows_null'] == true || $field['allows_null'] == "true" || $field['allows_null'] == 1))
    <option value="">-</option>
@endif
    @if (count($options))
        @foreach ($options as $option)
        <?php
        if(is_array($field['attribute'])){
            $attriputval=[];
            foreach ($field['attribute'] as $key => $value) {
                $attriputval[]=$option->$value;
            }
            $attriputval=implode(' - ',$attriputval);
        }else{
            $attriputval=$option->{$field['attribute']};
        }
        ?>
        @if($current_value == $option->getKey())
                    <option value="{{ $option->getKey() }}" selected>{{ $attriputval }}</option>
                @else
                    <option value="{{ $option->getKey() }}">{{ $attriputval }}</option>
                @endif
        @endforeach
    @endif
</select>

@if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
@push('after_styles')
@loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
@loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
@endpush
@push('after_scripts')
@loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
@loadScriptOnce('js/packages/select2/dist/js/i18n/'.$language.'.js')
@loadOnce('bpFieldInitSelect2Element')
@loadScriptOnce('js/Amer/forms/select2.js')
<script>
    bpFieldInitSelect2Element=function(element) {
        if (!element.hasClass("select2-hidden-accessible"))
                {
                    let $isFieldInline = element.data('field-is-inline');
                    let $disabled=element.data('read-only');
                    var uniqueid=$(element).attr('uniqueid');
                    registerSelect2WantedData(uniqueid);
                    select2f=setSelect2Info($(element).attr('uniqueid'));
                    element.select2(select2f);
                }
        }
</script>
@endLoadOnce
@endpush
