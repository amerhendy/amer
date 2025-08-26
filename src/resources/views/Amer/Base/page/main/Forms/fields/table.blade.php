<!-- Table Field Type -->
<?php
/*
    'name'=>'degree',
    //'id'=>'',
    'type'=>'table',
    'label'=>trans('ODLANG::MyOffice.office_degrees.office_degrees'),
    //'entity_singular'=>trans('ODLANG::MyOffice.office_degrees.office_degrees'),
    //'default'=>[],
    'min'=>-1,
    'max'=>-1,
    'sort'=>true,
    'changeColumn'=>false,
    'addRows'=>false,
    'columns'=>[
        'degree',
        ['start'=>['type'=>'date']],
        ['end'=>['type'=>'date']],
    ]
    'changeKeys'=>true,
*/
    if(!isset($field['placeholder'])){
                $field['placeholder']=$field['label'];
    }
    $max = isset($field['max']) && (int) $field['max'] > 0 ? $field['max'] : -1;
    $min = isset($field['min']) && (int) $field['min'] > 0 ? $field['min'] : -1;
    $item_name = strtolower(isset($field['entity_singular']) && ! empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);
    $items = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
    if (is_string($items) && ! is_array(json_decode($items)) && !empty($items)) {
        $items=[['value'=>'value']];
    }
    if (is_array($items)) {
        if(!isset($field["columns"])){
            if (count($items)) {
            $field['columns']=array_keys($items[0]);
            //$items = json_encode($items);

        } else {
            $items=[['value'=>'value']];
        }
        }
    }
    // make sure columns are defined
    if (!isset($field['columns'])) {
        $field['columns'] = [['value' => ['type'=>'text']]];
    }
    if(!isset($field['changeKeys'])){
        $field['changeKeys']=0;
    }else{
        if($field['changeKeys'] == false ||$field['changeKeys'] === "false" || $field['changeKeys'] === "0"){
            $field['changeKeys']=0;
        }else{
            $field['changeKeys']=1;
        }
    }
    if(!isset($field['id'])){$field['id']=$field['name'];}
    $cols=json_encode($field["columns"],  JSON_FORCE_OBJECT |  JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_INVALID_UTF8_IGNORE | JSON_INVALID_UTF8_SUBSTITUTE | JSON_NUMERIC_CHECK | JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_PRESERVE_ZERO_FRACTION | JSON_UNESCAPED_LINE_TERMINATORS | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    $items=json_encode($items, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK |  JSON_HEX_QUOT);
    if(!isset($field['changeColumn'])){$field['changeColumn'] = true;}
    if($field['changeColumn'] === true){$field['changeColumn']=1;}else{$field['changeColumn']=0;}
    if(!isset($field['sort'])){$field['sort'] = true;}
    if($field['sort'] === true){$field['sort']=1;}else{$field['sort']=0;}
//dd(trans('AMER::actions.'));
    //dd($field['changeColumn']);
?>
@include(fieldview('inc.wrapper_start'))
<div>
<label for="{{ $field['name'] }}" class="form-label">{!! $field['label'] ?? '' !!}</label>
@include(fieldview('inc.translatable_icon'))
</div>
    <input class="array-json"
            type="hidden"
            data-init-function="bpFieldInitTableElement"
            name="{{ $field['name'] }}"
            name="{{ $field['id'] }}"
            data-max="{{$max}}"
            data-min="{{$min}}"
            data-maxErrorTitle="{{ trans('AMER::Base.table_cant_add', ['entity' => $item_name]) }}"
            data-maxErrorMessage="{{ trans('AMER::Base.table_max_reached', ['max' => $max]) }}"
            data-phparray="{{ $items }}"
            data-columnsarray='{{$cols}}'
            data-changeColumn='{{$field['changeColumn']}}'
            data-sort='{{$field['sort']}}'
            data-changeKeys="{{$field['changeKeys']}}";
            >
    <div class="array-container form-group" table-name="{{ $field['name'] }}">
    </div>
    @if (isset($field['hint']))
        <small class="form-text text-muted">{!! $field['hint'] ?? '' !!}</small>
    @endif
@include(fieldview('inc.wrapper_end'))

    @push('after_styles')
    @loadStyleOnce('js/packages/select2/dist/css/select2.min.css')
    @loadStyleOnce('js/packages/select2-bootstrap-theme/dist/select2-bootstrap-5-theme.rtl.min.css')
    @loadStyleOnce('js/jquery/tablesorter-master/dist/css/theme.blue.css')
        <style>
            .move-column,.add-column,.delete-col,.move-row,.add-row,.add-row-header,.delete-row,.delete-row-header{
                display: inline-block;
                flex:0 0 auto 0;
                width:16px;
                height:16px;
                background-repeat:no-repeat;
            }
            .move-column{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16"><path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>');
            }
            .move-row{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16"><path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>');

            }
            .add-column,.add-row,.add-row-header{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16"><path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z"/></svg>');

            }
            .delete-col,.delete-row,.delete-row-header{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-square-fill" viewBox="0 0 16 16"><path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 7.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z"/></svg>');
            }
            .move-column,.move-row{
                cursor: move;
            }
            .add-column,.delete-col,.add-row,.delete-row,.delete-row-header,.add-row-header{
                cursor:pointer;
            }




            /*
                * dragtable
                *
                * @Version 2.0.0
                *
                * default css
                *
                */
                /*##### the dragtable stuff #####*/
                .dragtable-sortable {
                    list-style-type: none; margin: 0; padding: 0; -moz-user-select: none;
                }
                .dragtable-sortable li {
                    margin: 0; padding: 0; float: left; font-size: 1em; background: white;
                }

                .dragtable-sortable th, .dragtable-sortable td{
                    border-left: 0px;
                }

                .dragtable-sortable li:first-child th, .dragtable-sortable li:first-child td {
                    border-left: 1px solid #CCC;
                }

                .ui-sortable-helper {
                    opacity: 0.7;filter: alpha(opacity=70);
                }
                .ui-sortable-placeholder {
                    -moz-box-shadow: 4px 5px 4px #C6C6C6 inset;
                    -webkit-box-shadow: 4px 5px 4px #C6C6C6 inset;
                    box-shadow: 4px 5px 4px #C6C6C6 inset;
                    border-bottom: 1px solid #CCCCCC;
                    border-top: 1px solid #CCCCCC;
                    visibility: visible !important;
                    background: #EFEFEF !important;
                    visibility: visible !important;
                }
                .ui-sortable-placeholder * {
                    opacity: 0.0; visibility: hidden;
                }
        </style>
    @endpush
    @push('after_scripts')
    @loadScriptOnce('js/packages/select2/dist/js/select2.full.min.js')
    @loadScriptOnce('js/jquery/jquery-ui.min.js')
    @loadScriptOnce('js/jquery/jquery.dragtable.js')
    @loadScriptOnce('js/jquery/tablesorter-master/dist/js/jquery.tablesorter.js')
    @loadScriptOnce('js/Amer/forms/select2.js')
    @loadScriptOnce('js/Amer/forms/table.js')
    @loadOnce(bpFieldInitTableElement)
        <script>
            function movecoslumn(item){
                var thead=item.parent().parent();
                var tableName=thead.attr('table-name');
                var $serial=new Array();
                $.each($('table[table-name='+tableName+']').find('thead').find('th[data-number]'),function(k,v){
                    $serial.push($(v).attr('data-number'));
                })
                $.each($('table[table-name='+tableName+']').find('tbody').find('tr'),function(k,v){

                });
                //dd(item.attr('data-number'));
                //serialize th;
                //dd(thead.attr('table-name'));

            }
            function bpFieldInitTableElements(element) {



                // on page load, make sure the input has the old values
                updateTableFieldJson();
            }

        </script>
        @endLoadOnce
    @endpush
