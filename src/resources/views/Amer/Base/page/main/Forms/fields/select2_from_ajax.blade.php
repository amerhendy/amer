<!-- select2 from ajax multiple -->
<?php
    $connected_entity = new $field['model'];
    $connected_entity_key_name = $connected_entity->getKeyName();
    $old_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? false;
    if(is_numeric($old_value)){
        $old_value=[$connected_entity::find($old_value)];
    }
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
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>

<?php
if($old_value){
foreach($old_value as $a=>$item){
    if(is_object($item)){
        $item = $connected_entity->find($item);
    }
    
}
}
?>
    <select
        name="{{ $field['name'] }}@if (isset($field['multiple']) && $field['multiple']==true)[] @endif"
        id="{{ $field['name'] }}"
        style="width: 100%"
        data-model="{{$modelName}}"
        data-init-function="bpFieldInitSelect2FromAjax"
        data-field-is-inline="{{var_export($inlineCreate ?? false)}}"
        data-dependencies="{{ isset($field['dependencies'])?json_encode(Arr::wrap($field['dependencies'])): json_encode([]) }}"
        data-placeholder="{{ $field['placeholder'] }}"
        data-minimum-input-length="{{ $field['minimum_input_length'] }}"
        data-data-source="{{ $apilink}}"
        data-rel-ty="{{ $field['releationType'] ?? '' }}"
        data-method="{{ $field['method'] ?? 'GET' }}"
        data-field-attribute='@json($field['attribute'])'
        data-connected-entity-key-name="{{ $connected_entity_key_name }}"
        data-include-all-form-fields="{{ isset($field['include_all_form_fields']) ? ($field['include_all_form_fields'] ? 'true' : 'false') : 'false' }}"
        data-ajax-delay="{{ $field['delay'] }}"
        data-language="{{ str_replace('_', '-', app()->getLocale()) }}"
        data-arrayview='@json($field["array_view"] ?? [])'
        @include(fieldview('inc.attributes'), ['default_class' =>  'form-control'])
        @if (isset($field['multiple']) && $field['multiple']==true)multiple="true" @endif>
        @if ($old_value)
            @if (isset($field['multiple']) && $field['multiple']==true)
                    @foreach ($old_value as $item)
                        @if (!is_object($item))
                            @php
                                $item = $connected_entity->find($item);
                            @endphp
                        @endif
                        @if(is_null($item)) @continue @endif
                        <option value="{{ $item->getKey() }}" selected>
                            <?php
                            if(!is_array($field['attribute'])){
                            echo $item->{$field['attribute']}; 
                            }else{
                                $var=[];
                                foreach ($field['attribute'] as $key => $value) {
                                    if(isset($field['array_view']['enum'][$value][$item->{$value}])){
                                        $var[]= $field['array_view']['enum'][$value][$item->{$value}];
                                    }else{
                                        $var[]= $item->{$value}; 
                                    }
                                }
                                if(isset($field['array_view']['translate'])){
                                    echo Str::replaceArray('?', $var, $field['array_view']['translate']);
                                }else{
                                    if(isset($field['array_view']['divider'])){$divider=$field['array_view']['divider'];}else{$divider='-';}
                                    echo implode($divider,$var);
                                }
                            }
                            ?>
                        </option>
                    @endforeach
                @else
                @if (!is_object($item))
                            @php
                                $item = $connected_entity->find($item);
                            @endphp
                        @endif
                @if(is_array($old_value))
                @foreach($old_value as $a=>$item)
                    <option value="{{$item->getkey()}}" selected>{{ $item->{$field['attribute']} }}</option>
                @endforeach
                @else
                <option value="{{ $old_value->getKey() }}" selected>{{ $old_value->{$field['attribute']} }}</option>
                @endif
                
                @endif        
        @endif
    </select>
        @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
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
    @loadOnce('bpFieldInitSelect2FromAjax')
<script>
    function bpFieldInitSelect2FromAjax(element) {
        var form = element.closest('form');
        var $placeholder = element.attr('data-placeholder');
        var $minimumInputLength = element.attr('data-minimum-input-length');
        var $dataSource = element.attr('data-data-source');
        var $method = element.attr('data-method');
        var $fieldAttribute = element.attr('data-field-attribute');
        var $datarelty = element.attr('data-rel-ty');
        var $connectedEntityKeyName = element.attr('data-connected-entity-key-name');
        var $includeAllFormFields = element.attr('data-include-all-form-fields')=='false' ? false : true;
        var $allowClear = element.attr('data-column-nullable') == 'true' ? true : false;
        var $dependencies = JSON.parse(element.attr('data-dependencies'));
        var $ajaxDelay = element.attr('data-ajax-delay');
        var $selectedOptions = typeof element.attr('data-selected-options') === 'string' ? JSON.parse(element.attr('data-selected-options')) : JSON.parse("[]");
        var $isFieldInline = element.data('field-is-inline');
        var $arrayview=element.data('arrayview');
        var $multiple=element.attr('multiple');
        if($multiple == 'multiple'){$multiple=true;}else{$multiple=false;}
//setdepval
////////////////////////////
var SELECT_ALL_LIMIT = 1000;
$.fn.select2.amd.define('select2/selectAllAdapter', [
  'select2/utils',
  'select2/dropdown',
  'select2/dropdown/attachBody'
], function(Utils, Dropdown, AttachBody) {
  function SelectAll() {}

  SelectAll.prototype.render = function(decorated) {
    var $rendered = decorated.call(this);
    var self = this;

    var $selectAll = $('<button class="btn btn-xs btn-default btn-outline-secondary select_all" style="width:100%;margin-top: 5px;" type="button">{{trans("AMER::actions.selectall")}}</button>');

    var checkOptionsCount = function() {
      var count = $('.select2-results__option').length;
      $selectAll.text('{{trans("AMER::actions.selectall")}} (' + count + ')');
      $selectAll.prop('disabled', count > SELECT_ALL_LIMIT);
    }


    var $container = $('.select2-container');
    $container.bind('keyup click', checkOptionsCount);

    var $dropdown = $rendered.find('.select2-dropdown')
    $dropdown.prepend($selectAll);
    $selectAll.on('click', function(e) {
      var $results = $rendered.find('.select2-results__option[aria-selected=false]');

      // Get all results that aren't selected
      $results.each(function() {
        var $result = $(this);
        var $div=$result.children();
        var $id=$($div).data('id');
        var $text=$($div).text();
        var data={id:$id,text:$text};

        // Trigger the select event
        self.trigger('select', {
          data: data
        }); // <-- TypeError, data is undefined
      });

      self.trigger('close');
    });
    return $rendered;
  };

  return Utils.Decorate(
    Utils.Decorate(
      Dropdown,
      AttachBody
    ),
    SelectAll
  );
})

/////////////////////////////////
        var select2AjaxMultipleFetchSelectedEntries = function (element) {
            return new Promise(function (resolve, reject) {
                $.ajax({
                    url: $dataSource,
                    data: {
                        'keys': $selectedOptions
                    },
                    type: $method,
                    success: function (result) {

                        resolve(result);
                    },
                    error: function (result) {
                        reject(result);
                    }
                });
            });
        };

        if (!$(element).hasClass("select2-hidden-accessible"))
        {
            var formatter = new RepositoryFormatter()
            $(element).select2({
                theme: 'bootstrap-5',
                multiple: $multiple,
                allowClear: $allowClear,
                placeholder: $placeholder,
                minimumInputLength: $minimumInputLength,
                dropdownParent: $isFieldInline ? $('#inline-create-dialog .modal-content') : document.body,
                templateResult: formatRepo,
                templateSelection:formatRepoSelection,
                dropdownAdapter: $.fn.select2.amd.require('select2/selectAllAdapter'),
                closeOnSelect: true ,
                ajax: {
                    url: $dataSource,
                    type: $method,
                    dataType: 'json',
                    delay: $ajaxDelay,
                    data: function (params) {
                        var $depfordata=[];
                            $dependencies.forEach((err)=>{
                                var inpval=$(form.find('[name="'+err+'"], [name="'+err+'[]"]')).val();
                                $depfordata.push({input:err,val:inpval,rel:$datarelty,attributes:@json($field['attribute'])});
                            });
                        if ($includeAllFormFields) {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                form: form.serializeArray(), // all other form inputsa
                                dependencies:$depfordata,
                            };
                        } else {
                            return {
                                q: params.term, // search term
                                page: params.page, // pagination
                                dependencies:$depfordata,
                            };
                        }
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: $.map(data['data'], function (item) {
                                dod='';
                                if(IsValidJSONString($fieldAttribute)){
                                    var attArr=JSON.parse($fieldAttribute);
                                    if(!Array.isArray(attArr)){
                                        dod=item[attArr];
                                    }else{
                                        let obj={};
                                        $.each(attArr,function(index, element){
                                            vars=item[element];
                                            if($arrayview.length === 0){var divider='-';}else{
                                                if(objkey_exists($arrayview,'enum')){
                                                    if(objkey_exists($arrayview['enum'],element)){
                                                        if(objkey_exists($arrayview['enum'][element],vars)){
                                                            vars=$arrayview['enum'][element][vars];
                                                        }
                                                    }
                                                }
                                            }
                                            obj[element]=vars;
                                        });
                                        if(objkey_exists($arrayview,'translate')){
                                                dod=replacestr($arrayview['translate'],obj);
                                        }else{
                                            dod=obj.join(divider);
                                        }
                                    }
                                }
                                return {
                                    text: dod,
                                    id: item[$connectedEntityKeyName]
                                }
                                /*
                                return{
                                    text:'sd',
                                    id:'111'
                                }
                                return {
                                    text: item[$fieldAttribute],
                                    id: item[$connectedEntityKeyName]
                                }
                                */
                            }),
                            pagination: {
                                 more: data.current_page < data.last_page
                            }
                        };
                    },
                    cache: true
                },
            });
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
function replacestr(str,$replace){
    $len=(str.match(/\?/g) || []).length;
    $find =Array($len);
    $find.fill('?');
    $replace=Object.values($replace)
    String.prototype.replaceArray = function(find, replace) {
  var replaceString = this;
  for (var i = 0; i < find.length; i++) {
    replaceString = replaceString.replace(find[i], replace[i]);
  }
  return replaceString;
};
var textarea = str;
var find = $find;
var replace = $replace;
textarea = textarea.replaceArray(find, replace);
    return textarea
}
</script>
@endLoadOnce
@endpush
