<!-- select2 multiple -->
@php
    if (!isset($field['options'])) {
        $field['options'] = $field['model']::all();
    } else {
        $field['options'] = call_user_func($field['options'], $field['model']::query());
    }

    //build option keys array to use with Select All in javascript.
    $model_instance = new $field['model'];
    $options_ids_array = $field['options']->pluck($model_instance->getKeyName())->toArray();

    $field['multiple'] = $field['multiple'] ?? true;
    $field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']);
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
        name="{{ $field['name'] }}[]"
        id="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2MultipleElement"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-select-all="{{ var_export($field['select_all'] ?? false)}}"
        data-options-for-js="{{json_encode(array_values($options_ids_array))}}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_multiple'])
        {{ $field['multiple'] ? 'multiple' : '' }}>

        @if ($field['allows_null'])
            <option value="">-</option>
        @endif

        @if (isset($field['model']))
            @foreach ($field['options'] as $option)
                @if( (old(square_brackets_to_dots($field["name"])) && in_array($option->getKey(), old($field["name"]))) || (is_null(old(square_brackets_to_dots($field["name"]))) && isset($field['value']) && in_array($option->getKey(), $field['value']->pluck($option->getKeyName(), $option->getKeyName())->toArray())))
                    <option value="{{ $option->getKey() }}" selected>{{ $option->{$field['attribute']} }}</option>
                @else
                    <option value="{{ $option->getKey() }}">{{ $option->{$field['attribute']} }}</option>
                @endif
            @endforeach
        @endif
    </select>

    @if(isset($field['select_all']) && $field['select_all'])
        <a class="btn btn-xs btn-default select_all" style="margin-top: 5px;"><i class="fa fa-check-square-o"></i> {{ trans('AMER::actions.select_all') }}</a>
        <a class="btn btn-xs btn-default clear" style="margin-top: 5px;"><i class="fa fa-times"></i> {{ trans('AMER::actions.clear') }}</a>
    @endif
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
    @if (app()->getLocale() !== 'en')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
    @loadOnce('bpFieldInitSelect2MultipleElement')
        <script>
            function bpFieldInitSelect2MultipleElement(element) {

                var $select_all = element.attr('data-select-all');
                if (!element.hasClass("select2-hidden-accessible"))
                {
                    let $isFieldInline = element.data('field-is-inline');
                    select2f=setSelect2Info($(element).attr('uniqueid'));
                    var $obj = $(element).select2(select2f);

                    //get options ids stored in the field.
                    var options = JSON.parse(element.attr('data-options-for-js'));

                    if($select_all) {
                        element.parent().find('.clear').on("click", function () {
                            $obj.val([]).trigger("change");
                        });
                        element.parent().find('.select_all').on("click", function () {
                            $obj.val(options).trigger("change");
                        });
                    }
                }
            }
        </script>
        @endLoadOnce
    @endpush
