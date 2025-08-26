<!-- select2 from ajax multiple -->
<?php
    $fieldName=$field['name'].($field['multiple']?'[]':'');
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    $field['minimum_input_length']=$field['minimum_input_length'] ?? 0 ;
    $field['language']=str_replace('_', '-', app()->getLocale());
    $field['allows_null'] = $field['allows_null'] ?? $Amer->model::isColumnNullable($field['name']);
    ///prep field attribute
    if(!is_array($field['attribute'])){
        $field['attribute']=[$field['attribute']];
    }
    $attrs=$field['attribute'];
    if(in_array('id',$attrs)){
        unset($attrs[array_search('id',$attrs)]);
    }
    $attrs=\Arr::prepend($attrs, 'id');
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    if(is_numeric($old_value)){
        $old_value=$connected_entity::find($old_value);
    }elseif (\Str::isUuid($old_value)) {
        $old_value=$connected_entity::find($old_value);
    }
    $old_value_ids = $old_value->pluck($connected_entity->getKeyName())->toArray();
    $modelNameArray=explode('\\',$field['model']);
    $modelName=$modelNameArray[count($modelNameArray)-1];
    // by default set ajax query delay to 500ms
    // this is the time we wait before send the query to the search endpoint, after the user as stopped typing.
    $field['delay'] = $field['delay'] ?? 500;
    if(is_array($field['data_source'])){
        if(count($field['data_source']) == 1){
            $apilink=Route($field['data_source'][0]);
        }else{
            $apilink=Route($field['data_source'][0],$field['data_source'][1]);
        }
    }else{
        $apilink=Route($field['data_source']);
    }
    $field['multiple'] = $field['allows_multiple'] ?? $field['multiple'] ?? false;
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ? $field['placeholder']:'' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
        name="{{ $fieldName }}"
        id="{{ $field['name'] }}"
        style="width: 100%"
        data-placeholder="{{ $field['placeholder'] }}"
        data-minimum-input-length="{{ $field['minimum_input_length'] }}"
        data-init-function="bpFieldInitSelect2FromAjax"
        data-model="{{$modelName}}"
        data-connected-entity-key-name="{{ $connected_entity_key_name }}"
        data-column-nullable="{{ $field['allows_null'] }}"
        data-select-all="{{ var_export($field['select_all'] ?? false)}}"
        data-options-for-js="{{json_encode($old_value)}}"
        data-selected-options="{{json_encode($old_value_ids)}}"
        data-language="{{ $field['language'] }}"
        data-field-attribute='@json($field['attribute'])'
        data-arrayview='@json($field["array_view"] ?? [])'
        data-dependencies="{{ isset($field['dependencies'])?json_encode(Arr::wrap($field['dependencies'])): json_encode([]) }}"
        data-data-source="{{ $apilink}}"
        data-rel-ty="{{ $field['releationType'] ?? '' }}"
        data-method="{{ $field['method'] ?? 'POST' }}"
        data-include-all-form-fields="{{ isset($field['include_all_form_fields']) ? ($field['include_all_form_fields'] ? 'true' : 'false') : 'false' }}"
        data-ajax-delay="{{ $field['delay'] }}"
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control'])
        @if (isset($field['multiple']) && $field['multiple']==true)multiple="true" @endif>
    </select>
        @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))
    @push('after_styles')
    @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
    @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.min.css')
    @endpush
    @push('after_scripts')
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @if (app()->getLocale() !== 'en')
    @loadScriptOnce('js/packages/select2/dist/js/i18n/' . str_replace('_', '-', app()->getLocale()) . '.js')
    @endif
    @loadScriptOnce('js/Amer/forms/select2.js')
    @loadScriptOnce('js/Amer/forms/select2_from_ajax.js')
@endpush
