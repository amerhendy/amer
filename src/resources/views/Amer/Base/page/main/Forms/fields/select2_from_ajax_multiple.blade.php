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

?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
        name="{{ $fieldName }}"
        id="{{ $field['name'] }}"
        style="width: 100%"
        data-placeholder="{!! $field['placeholder']!!}"
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
        data-data-source="{{ $apilink }}"
        data-rel-ty="{{ $field['releationType'] ?? '' }}"
        data-method="{{ $field['method'] ?? 'post' }}"
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
    @loadOnce('bpFieldInitSelect2FromAjaxMultipleElement')
<script>
    function bpFieldInitSelect2FromAjaxMultipleElements(element) {
        var form = element.closest('form');
        var $placeholder = element.attr('data-placeholder');
        var $minimumInputLength = element.attr('data-minimum-input-length');
        var $dataSource = element.attr('data-data-source');
        var $method = element.attr('data-method');
        var $fieldAttribute = element.attr('data-field-attribute');
        var $connectedEntityKeyName = element.attr('data-connected-entity-key-name');
        var $includeAllFormFields = element.attr('data-include-all-form-fields')=='false' ? false : true;
        var $allowClear = element.attr('data-column-nullable') == 'true' ? true : false;
        var $dependencies = JSON.parse(element.attr('data-dependencies'));
        var $ajaxDelay = element.attr('data-ajax-delay');
        var $selectedOptions = typeof element.attr('data-selected-options') === 'string' ? JSON.parse(element.attr('data-selected-options')) : JSON.parse("[]");
        var $isFieldInline = element.data('field-is-inline');
        var $model=element.attr('data-model');

        var select2AjaxMultipleFetchSelectedEntries = setPromiseSelect2($(element).attr('uniqueid'));

        if (!$(element).hasClass("select2-hidden-accessible"))
        {
var formatter = new RepositoryFormatter()
/////////////////////////////////
            select2f=setSelect2Info($(element).attr('uniqueid'));
            select2f['ajax']=setSelect2BasicAjax($(element).attr('uniqueid'));
            select2f['ajax']['data']=function (params) {
                var sentdata={q: params.term,page: params.page,};
                if ($includeAllFormFields) {sentdata.form=form.serializeArray()}else{}
                if(exists($dependencies)){
                    $($dependencies).each(function(index,value){
                        val=form.find('[name="'+value+'"], [name="'+value+'[]"]').val()
                        homemodel=form.find('[name="'+value+'"], [name="'+value+'[]"]').attr('data-model');
                        wanted=$model;
                        sentdata.dependencies={homemodel,wanted,val,attributes:$fieldAttribute}
                    });
                }

                return sentdata;
            };
            select2f['ajax']['processResults']=function (data, params) {
                params.page = params.page || 1;
                return {
                    results: $.map(data.data, function (item) {
                        if(IsValidJSONString($fieldAttribute)){
                            parsed = JSON.parse($fieldAttribute);
                            parsed.forEach((item)=>{
                                datype=typeof(item);
                                if(datype === 'object'){
                                    newfieldAttribute=Object.keys(item);
                                }
                                if(datype === 'string'){
                                    newfieldAttribute=item;
                                }
                            });
                        }
                        //$fieldAttribute=JSON.parse($fieldAttribute);
                        //dd($connectedEntityKeyName);
                        return {
                            text: item[newfieldAttribute],
                            id: item.id
                        }
                    }),
                    pagination: {
                            more: data.current_page < data.last_page
                    }
                };
            };
            $(element).select2(select2f);
        }
        function RepositoryFormatter() {}
function formatRepo(repo) {
  if (repo.loading) {
    return repo.text;
  }
  var markup = $("<div class='select2-result-repository__title' data-id='" + repo.id + "'>" + repo.text + "</div>");
  return markup;
}
function formatRepoSelection(repo) {
  return repo.full_name || repo.text;
}
        // if we have selected options here we are on a repeatable field, we need to fetch the options with the keys
        // we have stored from the field and append those options in the select.
        if (typeof $selectedOptions !== typeof undefined && $selectedOptions !== false && $selectedOptions != '') {
            var optionsForSelect = [];
            select2AjaxMultipleFetchSelectedEntries(element).then(function(result) {
                result.forEach(function(item) {
                    $itemText = item[$fieldAttribute];
                    $itemValue = item[$connectedEntityKeyName];
                    //add current key to be selected later.
                    optionsForSelect.push($itemValue);

                    //create the option in the select
                    $(element).append('<option value="'+$itemValue+'">'+$itemText+'</option>');
                });

                // set the option keys as selected.
                $(element).val(optionsForSelect);
            });
        }

        // if any dependencies have been declared
        // when one of those dependencies changes value
        // reset the select2 value
        for (var i=0; i < $dependencies.length; i++) {
            var $dependency = $dependencies[i];
            //if element does not have a custom-selector attribute we use the name attribute
            if(typeof element.attr('data-custom-selector') == 'undefined') {
                form.find('[name="'+$dependency+'"], [name="'+$dependency+'[]"]').change(function(el) {
                        $(element.find('option:not([value=""])')).remove();
                        element.val(null).trigger("change");
                });
            }else{
                // we get the row number and custom selector from where element is called
                let rowNumber = element.attr('data-row-number');
                let selector = element.attr('data-custom-selector');

                // replace in the custom selector string the corresponding row and dependency name to match
                selector = selector
                    .replaceAll('%DEPENDENCY%', $dependency)
                    .replaceAll('%ROW%', rowNumber);

                $(selector).change(function (el) {
                    $(element.find('option:not([value=""])')).remove();
                    element.val(null).trigger("change");
                });
            }
        }
    }
</script>
@endLoadOnce
@endpush
