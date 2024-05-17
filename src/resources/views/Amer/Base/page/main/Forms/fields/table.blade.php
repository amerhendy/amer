<!-- Backpack Table Field Type -->
<?php
    $max = isset($field['max']) && (int) $field['max'] > 0 ? $field['max'] : -1;
    $min = isset($field['min']) && (int) $field['min'] > 0 ? $field['min'] : -1;
    $item_name = strtolower(isset($field['entity_singular']) && ! empty($field['entity_singular']) ? $field['entity_singular'] : $field['label']);
    $items = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
    if (is_string($items) && ! is_array(json_decode($items))) {
        $items=[['value'=>'value']];
    }
    if (is_array($items)) {
        if (count($items)) {
            $field['columns']=array_keys($items[0]);
            //$items = json_encode($items);

        } else {
            $items=[['value'=>'value']];
        }
    }
    // make sure columns are defined
    if (! isset($field['columns'])) {
        $field['columns'] = [['value' => 'Value']];
    }
    if(!isset($field['id'])){$field['id']=$field['name'];}
    
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
            data-maxErrorTitle="{{trans('Amer::base.table_cant_add', ['entity' => $item_name])}}"
            data-maxErrorMessage="{{trans('Amer::base.table_max_reached', ['max' => $max])}}">            
    <div class="array-container form-group" table-name="{{ $field['name'] }}">
        <div class="array-controls btn-group m-t-10">
            <button class="btn btn-sm btn-light" type="button" data-button-type="addItem"><i class="fa fa-plus"></i> {{trans('Amer::base.add')}} {{ $item_name }}</button>
        </div>

    </div>
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include(fieldview('inc.wrapper_end'))
    @push('after_scripts')
        @loadStyleOnce('css/jquery-ui-1.10.0.custom.min.css')
        <style>
            .move-column,.add-column,.delete-col,.move-row,.add-row,.delete-row{
                display: inline-block;
                flex:0 0 auto 0;
                width:16px;
                height:16px;
            }
            .move-column{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-horizontal" viewBox="0 0 16 16"><path d="M2 8a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm3 3a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm0-3a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>');    
            }
            .move-row{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-grip-vertical" viewBox="0 0 16 16"><path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>');
            }
            .add-column,.add-row{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-square-fill" viewBox="0 0 16 16"><path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm6.5 4.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3a.5.5 0 0 1 1 0z"/></svg>');
            }
            .delete-col,.delete-row{
                background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash-square-fill" viewBox="0 0 16 16"><path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm2.5 7.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1z"/></svg>');
            }
            .move-column,.move-row{
                cursor: move;
            }
            .add-column,.delete-col,.add-row,.delete-row{
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
    @loadScriptOnce('js/jquery/jquery-ui.min.js')
    @loadScriptOnce('js/jquery/jquery.dragtable.js')
    @loadScriptOnce('js/jquery/jquery.tablesorter.min.js')
    @loadOnce(bpFieldInitTableElement)
        <script>
            function movecolumn(item){
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
            function bpFieldInitTableElement(element) {
                var $tableWrapper = element.parent('[data-field-type=table]');
                var $max = element.attr('data-max');
                var $min = element.attr('data-min');
                var $maxErrorTitle = element.attr('data-maxErrorTitle');
                var $maxErrorMessage = element.attr('data-maxErrorMessage');
                var phparray={{ Illuminate\Support\Js::from($items) }};
                var columnsarray={{ Illuminate\Support\Js::from($field['columns'])}};
                var tableName=element.attr('name');
                    element.val(JSON.stringify(phparray))
                //var $rows = (element.attr('value') != '') ? $.parseJSON(element.attr('value')) : '';
                    createTable(tableName);
                    createHeader(tableName);
                    createTbody(tableName);
                    prepareTableNumbers(tableName);
            $("table[table-name="+tableName+"]").sortable({ items: "tr.sortable" })
            .dragtable({
                dragHandle: ".move-column",
                maxMovingRows:1,
                persistState: function(table) { 
                    prepareTableNumbers(tableName)
            }}).
            tablesorter();
                $tableWrapper.find('.sortableOptions').sortable({
                    handle: '.sort-handle',
                    axis: 'y',
                    helper: function(e, ui) {
                        ui.children().each(function() {
                            $(this).width($(this).width());
                        });
                        return ui;
                    },
                    update: function( event, ui ) {
                        updateTableFieldJson();
                    }
                });
                $tableWrapper.find('.thead').sortable({
                    handle: '.sort-handle',
                    axis: 'x',
                    helper: function(e, ui) {
                        ui.children().each(function() {
                            $(this).width($(this).width());
                        });
                        return ui;
                    },
                    update: function( event, ui ) {
                        updateTableFieldJson();
                    }
                });
                $tableWrapper.find('.add-column').on('click', function(e) {
                    
                    tar=e.target
                    var number=$(tar).attr('data-number')
                    var thead=$(tar).parent().parent().parent().parent().parent();
                    var tableName=thead.attr('table-name')
                        addcol(number,tableName);
                        prepareTableNumbers(tableName)
                        updateTableFieldJson();
                    });
                $tableWrapper.on('click', '.add-column', function(e) {
                    tar=e.target
                    var number=$(tar).attr('data-number')
                    var thead=$(tar).parent().parent().parent().parent().parent();
                    var tableName=thead.attr('table-name')
                        addcol(number,tableName);
                        prepareTableNumbers(tableName)
                        updateTableFieldJson();
                    });
                $tableWrapper.on('click', '.delete-col', function(e) {
                    tar=e.target
                    var number=$(tar).attr('data-number')
                    var thead=$(tar).parent().parent().parent().parent().parent();
                    var tableName=thead.attr('table-name')
                    removecol(number,tableName);
                    prepareTableNumbers(tableName)
                    updateTableFieldJson();
                });
                $tableWrapper.find('.delete-col').on('click', function(e) {
                    tar=e.target
                    var number=$(tar).attr('data-number')
                    var thead=$(tar).parent().parent().parent().parent().parent();
                    var tableName=thead.attr('table-name')
                    removecol(number,tableName);
                    prepareTableNumbers(tableName)
                    updateTableFieldJson();
                });
                $tableWrapper.on('click', '.add-row', function(e) {
                    tar=e.target
                    var number=$(tar).attr('data-number')
                    var tbody=$(tar).parent().parent().parent().parent().parent();
                    var tableName=tbody.attr('table-name')
                    addrow(number,tableName);
                    prepareTableNumbers(tableName)
                    updateTableFieldJson();
                });
                $tableWrapper.on('click', '.delete-row', function(e) {
                    tar=e.target
                    var number=$(tar).attr('data-number')
                    var tbody=$(tar).parent().parent().parent().parent().parent();
                    var tableName=tbody.attr('table-name')
                    var tr=$('table[table-name='+tableName+']').find('tbody').find('tr[rowid='+number+']');
                    $(tr).remove();
                    prepareTableNumbers(tableName)
                    updateTableFieldJson()
                });
                $tableWrapper.find('.delete-row').on('click',  function(e) {
                    tar=e.target
                    var number=$(tar).attr('data-number')
                    var tbody=$(tar).parent().parent().parent().parent().parent();
                    var tableName=tbody.attr('table-name')
                    var tr=$('table[table-name='+tableName+']').find('tbody').find('tr[rowid='+number+']');
                    $(tr).remove();
                    prepareTableNumbers(tableName)
                    updateTableFieldJson()
                });
                function createTable(Name){
                    var table=$('<table></table>');
                    table.attr('table-name',Name);
                    table.append($('<thead table-name='+Name+'></thead>'));
                    table.append($('<tbody table-name='+Name+'></tbody>'));
                    $('div[table-name='+Name+']').html(table);
                }
                function createHeader(Name){
                    var tr=$('<tr></tr>');
                    var th=$('<th datatype="actions"></th>');
                    tr.append(th);
                    for(i=0;i<columnsarray.length;i++){
                        var th=`<th style="font-weight: 600!important;" data-number="`+i+`">
                        <div class="dragHandle move-column"></div>
                        <div class="add-column" data-number="`+i+`"></div>
                        <div class="delete-col" data-number="`+i+`"></div>
                        <input class="form-control form-control-sm" type="text" data-cell-name="item.`+i+`" value="`+columnsarray[i]+`"></th>`;
                        tr.append(th);
                    }
                    $('thead[table-name='+Name+']').html(tr);
                }
                function createTbody(Name){
                    $.each(phparray,function(k,v){
                        var tr=$('<tr class="sortable" rowid="'+k+'"></tr>')
                        buttons=$(`
                        
                        <div class="dropdown">
                            <div class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-slash-minus" viewBox="0 0 16 16">
                                    <path d="m1.854 14.854 13-13a.5.5 0 0 0-.708-.708l-13 13a.5.5 0 0 0 .708.708ZM4 1a.5.5 0 0 1 .5.5v2h2a.5.5 0 0 1 0 1h-2v2a.5.5 0 0 1-1 0v-2h-2a.5.5 0 0 1 0-1h2v-2A.5.5 0 0 1 4 1Zm5 11a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5A.5.5 0 0 1 9 12Z"/>
                                </svg>
                            </div>
                            <ul class="dropdown-menu">
                            <div class="dragHandle move-row"></div>
                                <div class="add-row" data-number="`+i+`"></div>
                                <div class="delete-row" data-number="`+i+`"></div>
                            </ul
                        </div>
                        `)
                        var td=$('<td data-type="actions"></td>');
                        td.append(buttons);
                        tr.append(td);
                        $.each(v,function(l,m){
                            var td=`<td  data-number="`+m+`">
                                        <input class="form-control form-control-sm" type="text" data-cell-name="item.`+l+`" value="`+m+`">
                                    </td>`;
                            tr.append(td);
                        })
                        //$(tr).children()[0].prepend($(buttons)[0])
                        $('tbody[table-name='+Name+']').append(tr);
                    })
                }
                
                function addItem() {
                    $tableWrapper.find('tbody').append($tableWrapper.find('tbody .clonable').clone().show().removeClass('clonable'));
                }
                
                function addcol(number,tableName) {
                    var th=$('table[table-name='+tableName+']').find('thead').find('tr').find('th[data-number='+number+']');
                    var dropdownmenu=$(th).find('.dropdown-menu');
                    $(dropdownmenu).dropdown('toggle');
                    var cloneth=$('th[data-number='+number+']').clone();
                    $(cloneth).insertAfter($(th));
                    var td=$('table[table-name='+tableName+']').find('tbody').find('tr').find('td[data-number='+number+']');
                    $.each(td,function(a,b){
                        var tdclone=$(b).clone();
                        $(tdclone).find('input').val('');
                        $(tdclone).insertAfter($(b))
                    })
                }
                function addrow(number,tableName){
                    var tr=$('table[table-name='+tableName+']').find('tbody').find('tr[rowid='+number+']');
                    var dropdownmenu=$(tr).find('.dropdown-menu');
                    $(dropdownmenu).dropdown('toggle');
                    var clonetr=$(tr).clone();
                    $(clonetr).insertAfter($(tr));
                    var input=$(clonetr).find('input');
                    $.each(input,function(a,b){
                        $(b).val('');
                    })
                    
                }
                function removecol(number,tableName){
                    var th=$('table[table-name='+tableName+']').find('thead').find('tr').find('th[data-number='+number+']');
                    var dropdownmenu=$(th).find('.dropdown-menu');
                    $(dropdownmenu).dropdown('toggle');
                    $(th).remove();
                    var td=$('table[table-name='+tableName+']').find('tbody').find('tr').find('td[data-number='+number+']');
                    $.each(td,function(a,b){
                        $(b).remove()
                    })
                }
                function prepareTableNumbers(Name){
                    var table=$('table[table-name='+Name+']');
                    prepareHeaderNumber(table)
                    prepareTbodyNumber(table)
                }
                function prepareTbodyNumber(table){
                    tr=$(table).find('tbody').find('tr');
                    var trlength=$(tr).length;
                    trarray=[ ...Array(trlength).keys() ].map( i => i+0)
                    $.each(trarray,function(n){
                        var vol=$(tr)[n];
                        $.each($(vol).find('td[data-number]'),function(l,m){
                            $(m).attr('data-number',l);
                            $.each($(m).find('input'),function(a,b){
                                $(b).attr('data-cell-name','item.'+l);
                            })
                        })
                        $.each($(vol).find('td[data-type=actions]'),function(l,m){
                            $.each($(m).find('[data-number]'),function(a,b){
                                $(b).attr('data-number',n);
                            })
                            $.each($(m).find('button'),function(a,b){
                                $(b).attr('id','row-'+n);
                            })
                            $.each($(m).find('ul'),function(a,b){
                                $(b).attr('aria-labelledby','row-'+n);
                            })
                        })
                    });
                    $.each(trarray,function(n){
                        var vol=$(tr)[n];
                        $(vol).attr('rowid',n)
                    })
                }
                function prepareHeaderNumber(table){
                    th=$(table).find('th[data-number]');
                    var thlength=$(th).length;
                    tharray=[ ...Array(thlength).keys() ].map( i => i+0)
                    $.each(tharray,function(n){
                        var vol=$(th)[n];
                        input = $(vol).find('input')
                        $(input).attr('data-cell-name','item.'+n)
                        $.each($(vol).find('[data-number]'),function(l,m){
                            $(m).attr('data-number',n);
                        })
                        $.each($(vol).find('button'),function(l,m){
                            $(m).attr('id','col-'+n);
                            $(m).removeClass('show');
                        })
                        $.each($(vol).find('ul'),function(l,m){
                            $(m).attr('aria-labelledby','col-'+n);
                            $(m).attr('style','');
                            $(m).removeClass('show');
                        })
                        $(vol).attr('data-number',n);
                    });
                }
                $tableWrapper.find('tbody').on('keyup', function() {
                    updateTableFieldJson();
                });
                function updateTableFieldJson() {
                    var myTable = { myTable: [] };
                    var $hiddenField = $tableWrapper.find('input.array-json');
                    var $th = $('table th[data-number]');
                    $('table tbody tr').each(function(i, tr){
                        var obj = {}, $tds = $(tr).find('td[data-number]');
                        $th.each(function(index, th){
                            obj[$(th).find('input').val()] = $tds.find('input').eq(index).val();
                        });
                        myTable.myTable.push(obj);
                    });
                    $json=JSON.stringify(myTable.myTable, null, 2)
                    $hiddenField.val($json);
                }
                // on page load, make sure the input has the old values
                updateTableFieldJson();
            }
        </script>
        @endLoadOnce
    @endpush
