<!-- select2 from ajax multiple -->
<?php
$model=explode('\\',$field['model']);
$model=$model[count($model)-1];
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
    $pailink=Route($field['data_source']);
    if(!is_array($field['attribute'])){
        $requestedcolumn=json_encode([$field['attribute']]);
    }else{
        $requestedcolumn=json_encode($field['attribute']);
    }
    // by default set ajax query delay to 500ms
    // this is the time we wait before send the query to the search endpoint, after the user as stopped typing.
    $field['delay'] = $field['delay'] ?? 500;
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <select
        name="{{ $field['name'] }}[]"
        id="{{ $field['name'] }}"
        style="width: 100%"
        data-init-function="bpFieldInitSelect2FromAjaxMultipleElement"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-dependencies="{{ isset($field['dependencies'])?json_encode(Arr::wrap($field['dependencies'])): json_encode([]) }}"
        data-placeholder="{{ $field['placeholder'] }}"
        data-minimum-input-length="{{ $field['minimum_input_length'] }}"
        data-data-source="{{ $pailink }}"
        data-method="{{ $field['method'] ?? 'GET' }}"
        data-field-attribute="{{$requestedcolumn}}"
        data-connected-entity-key-name="{{ $connected_entity_key_name }}"
        data-include-all-form-fields="{{ isset($field['include_all_form_fields']) ? ($field['include_all_form_fields'] ? 'true' : 'false') : 'false' }}"
        data-ajax-delay="{{ $field['delay'] }}"
        data-model="{{$model}}";
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control'])
        multiple>

        @if ($old_value)
            @foreach ($old_value as $item)
                @if (!is_object($item))
                    @php
                        $item = $connected_entity->find($item);
                    @endphp
                @endif
                <option value="{{ $item->getKey() }}" selected>
                    {{ $item->{$field['attribute']} }}
                </option>
            @endforeach
        @endif
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
    @loadOnce('bpFieldInitSelect2FromAjaxMultipleElement')
<script>
    function bpFieldInitSelect2FromAjaxMultipleElement(element) {
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
