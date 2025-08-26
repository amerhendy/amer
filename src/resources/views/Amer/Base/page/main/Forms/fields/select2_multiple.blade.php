<!-- select2 multiple -->
@php
    $fieldName=$field['name'].($field['multiple']?'[]':'');
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    $field['minimum_input_length']=$field['minimum_input_length'] ?? 0 ;
    $field['language']=str_replace('_', '-', app()->getLocale());
    ///prep field attribute
    if(!is_array($field['attribute'])){
        $field['attribute']=[$field['attribute']];
    }
    $attrs=$field['attribute'];
    if(in_array('id',$attrs)){
        unset($attrs[array_search('id',$attrs)]);
    }
    $attrs=\Arr::prepend($attrs, 'id');
////////////////////////get old data/////////////////////////////////
    $modelNameArray=explode('\\',$field['model']);
    $modelName=$modelNameArray[count($modelNameArray)-1];
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    if (!isset($field['options'])) {
        if(empty($attrs)){
            $field['options'] = $connected_entity::all();
        }else{
            $field['options'] = $connected_entity::get($attrs);
        }

    } else {
        if(empty($attrs)){}else{}
        $field['options'] = call_user_func($field['options'], $connected_entity::query());
    }
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
    if(is_numeric($old_value)){
        $old_value=$connected_entity::find($old_value);
    }elseif (\Str::isUuid($old_value)) {
        $old_value=$connected_entity::find($old_value);
    }
    if(!empty($old_value)){
        $old_value=$old_value->pluck($connected_entity->getKeyName())->toArray();
    }
    $model_instance = new $field['model'];
////////////////////////get old data/////////////////////////////////

    //$options_ids_array = data_get($options, '*.'.$model_instance->getKeyName());
    //dd($field['options']->toArray());
    $field['multiple'] = $field['multiple'] ?? true;
    $field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']);
@endphp
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
        name="{{ $fieldName }}"
        id="{{ $field['name'] }}"
        data-placeholder="{{ $field['placeholder'] }}"
        style="width: 100%"
        data-minimum-input-length="{{ $field['minimum_input_length'] }}"
        data-init-function="bpFieldInitSelect2MultipleElement"
        data-model="{{$modelName}}"
        data-connected-entity-key-name="{{ $connected_entity_key_name }}"
        data-column-nullable="{{ $field['allows_null'] }}"
        data-select-all="{{ var_export($field['select_all'] ?? false)}}"
        data-options-for-js="{{json_encode(array_values($field['options']->toArray()))}}"
        data-selected-options="{{json_encode(array_values($old_value))}}"
        data-language="{{ $field['language'] }}"
        data-field-attribute='@json($field['attribute'])'
        data-arrayview='@json($field["array_view"] ?? [])'
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control select2_multiple'])
        {{ $field['multiple'] ? 'multiple' : '' }}>
        @if ($field['allows_null'])
            <option value="">-</option>
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
    @loadScriptOnce('js/Amer/forms/select2.js')
    @loadScriptOnce('js/Amer/forms/select2_from_ajax.js')
        <script>
            function sbpFieldInitSelect2MultipleElement(element) {
                var uniqueid=$(element).attr('uniqueid');
                registerSelect2WantedData(uniqueid);
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
